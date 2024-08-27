<?php

namespace App\Services;

use App\Events\RegisteredEvent;
use App\Exceptions\Exception;
use App\Exceptions\NotFoundException;
use App\Exceptions\UserPasswordIsNotValid;
use App\Exceptions\UserNotActiveException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserNickNameExists;
use App\Helpers\EmailVerificationCodeHelper;
use App\Exceptions\UserIsBannedException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshRequest;
use App\Jobs\CommentsCountLikesJob;
use App\Jobs\FlowkCountCommentsJob;
use App\Models\Authorization;

use App\Models\User;
use App\Services\DeviceSessionService;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

final class UserService extends AbstractService
{
    private DeviceSessionService $deviceSessionService;

    public function __construct(UserRepository $userRepository, DeviceSessionService $deviceSessionService)
    {
        $this->repository           = $userRepository;
        $this->deviceSessionService = $deviceSessionService;
    }

    public function verifyByCode(string $code): void
    {
        $user = $this->repository->findByVerificationCode($code);
        if (!$user) {
            throw new NotFoundException('User is not found with provided code!');
        }
        $user->status                  = User::STATUS_ACTIVE;
        $user->email_verification_code = null;
        $user->email_verified_at       = Carbon::now();
        $user->save();
    }

    public function changePassword(int $userId, array $request): void
    {
        $user = $this->repository->findOneById($userId);

        if (!(Hash::check($request['current_password'], $user->password))) {
            throw new UserPasswordIsNotValid();
        }

        $user->password = Hash::make($request['password']);
        $user->save();
    }


    public function authenticateByArray(LoginRequest $request): Authorization
    {
        if (!($token = auth()->attempt($request->only(['email', 'password'])))) {
            throw new UserNotFoundException();        }

        // 403 if User not Active
        /** @var User $user */
        if (!$user = $this->repository->findByEmail($request->get('email'))) {
            if (!$user = $this->repository->findByNickName($request->get('email'))) {
                $user = $this->repository->findByPhone($request->get('email'));
            };
        }

        if ($user->status !== User::STATUS_ACTIVE) {
            throw new UserNotActiveException();
        }


        $socketToken = $this->deviceSessionService->processDeviceSession(
            $request->userAgent(),
            $request->get('device_id')
        );

        return new Authorization($token, $socketToken->auth_token);
    }

    /**
     * Tries to refresh authentication a user from the given data
     *
     * @param RefreshRequest $request
     *
     * @return Authorization
     */
    public function refreshByArray(RefreshRequest $request): Authorization
    {
        $socketToken = $this->deviceSessionService->processDeviceSession(
            $request->userAgent(),
            $request->get('device_id')
        );

        return new Authorization(auth()->refresh(), $socketToken->auth_token);
    }

    public function register(array $data): User
    {

        $data['email_verification_code'] = EmailVerificationCodeHelper::generate();
        $data['is_admin'] = false;
        $user                            = $this->repository->create($data);
        $user->assignRole(User::ROLE_USER);
        $user->givePermissionTo(User::getRolesPermissions(User::ROLE_USER));

        return $user;
    }

    public function generateRecoveryPasswordToken(User $user): User
    {
        $user->password_recovery_token            = $user->generatePasswordResetToken();
        $user->password_recovery_token_created_at = Carbon::now();
        $user->save();

        return $user;
    }

    public function generateRecoveryVerifyCode(User $user): User
    {
        $user->email_verification_code            = EmailVerificationCodeHelper::generate();
        $user->password_recovery_token            = $user->generatePasswordResetToken();
        $user->password_recovery_token_created_at = Carbon::now();
        $user->save();

        return $user;
    }

    public function processRecoveryPasswordToken(User $user, string $newPassword): User
    {
        $user->password                           = Hash::make($newPassword);
        $user->password_recovery_token            = null;
        $user->password_recovery_token_created_at = null;
        $user->save();

        if (!$user->save()) {
            throw new Exception('Password not changed');
        }

        return $user;
    }

    public function processRecoveryPasswordCode(User $user, string $newPassword): User
    {
//        var_dump($newPassword); die;
        $user->password                           = Hash::make($newPassword);
        $user->email_verification_code            = null;
        $user->password_recovery_token            = null;
        $user->save();

        if (!$user->save()) {
            throw new Exception('Password not changed');
        }

        return $user;
    }
    public function toggleFollowers(int $id): void
    {
        /** @var UserFollower $flowk */
        $follower      =  $this->repository->findOneById($id);
        $userFollower  = $this->repository->findByFollowerId($id);
        $currentUser = User::query()->where('id', auth()->id())->first();
        if (!$userFollower) {
            $userFolower = UserFollower::create([
                'user_id'   => auth()->id(),
                'follower_id'  => $id
            ]);
            $userFolower->save();
            if ($currentUser->pod_select_all == 1) {
                PodFollowing::create([
                    'user_id'       => auth()->id(),
                    'follower_id'      => $id,
                ]);
            }
            if (empty(Notifications::where(['user_id' => $id, 'autor_id' => auth()->id(), 'type'=> Notifications::TYPE_IS_FOLLOWER])
                ->get()->toArray())) {
                $notification = Notifications::create([
                    'user_id' => $id,
                    'autor_id' => auth()->id(),
                    'type' => Notifications::TYPE_IS_FOLLOWER,
                    'status' => Notifications::STATUS_ACTIVE,
                    'created_at' => time(),
                    'updated_at' => time(),
                ]);
                $notification->save();
            }
        } else {
            UserFollower::where([
                'user_id'   => auth()->id(),
                'follower_id'  => $follower->id
            ])->delete();
            PodFollowing::where([
                'user_id'   => auth()->id(),
                'follower_id'  => $follower->id
            ])->delete();
        }
    }

    public function toggleBlocker(int $id): void
    {
        /** @var UserBlocker $flowk */
        $locker      =  $this->repository->findOneById($id);
        $userLocker  = $this->repository->findByLockerId($id);
        if (!$userLocker) {
            $userLocker = UserBlocker::create([
                'blocker_id'   => auth()->id(),
                'locker_id'  => $id
            ]);
            $userLocker->save();
            $pinFlowks = Flowk::query()->where('created_by', $id)
                ->whereNotNull('pin_position')->get();
            if (!empty($pinFlowks)) {
                foreach ($pinFlowks as $pinFlowk) {
                    $currentVideoPinFlowks = Flowk::query()
                        ->where('ref_id', $pinFlowk->ref_id)
                        ->whereNotNull('pin_position')->orderBy('pin_position')->get();
                    $currentVideo = Flowk::query()->where('id', $pinFlowk->ref_id)->first();
                    if ($currentVideo->created_by == auth()->id()) {
                        foreach ($currentVideoPinFlowks as $currentVideoPinFlowk) {
                            if ($currentVideoPinFlowk->created_by == $id) {
                                $currentVideoPinFlowk->pin_position = null;
                                $currentVideoPinFlowk->save();
                            }
                        }
                        $currentVideoPinFlowks = Flowk::query()
                            ->where('ref_id', $pinFlowk->ref_id)
                            ->whereNotNull('pin_position')->orderBy('pin_position')->get();
                        foreach ($currentVideoPinFlowks as $key => $flowk) {
                            $flowk->pin_position = $key + 1;
                            $flowk->save();
                        }
                    }
                }
            }
            $pinFlowks = Flowk::query()->where('created_by', auth()->id())
                ->whereNotNull('pin_position')->get();
            if (!empty($pinFlowks)) {
                foreach ($pinFlowks as $pinFlowk) {
                    $currentVideoPinFlowks = Flowk::query()
                        ->where('ref_id', $pinFlowk->ref_id)
                        ->whereNotNull('pin_position')->orderBy('pin_position')->get();
                    $currentVideo = Flowk::query()->where('id', $pinFlowk->ref_id)->first();
                    if ($currentVideo->created_by == $id) {
                        foreach ($currentVideoPinFlowks as $currentVideoPinFlowk) {
                            if ($currentVideoPinFlowk->created_by == auth()->id()) {
                                $currentVideoPinFlowk->pin_position = null;
                                $currentVideoPinFlowk->save();
                            }
                        }
                        $currentVideoPinFlowks = Flowk::query()
                            ->where('ref_id', $pinFlowk->ref_id)
                            ->whereNotNull('pin_position')->orderBy('pin_position')->get();
                        foreach ($currentVideoPinFlowks as $key => $flowk) {
                            $flowk->pin_position = $key + 1;
                            $flowk->save();
                        }
                    }
                }
            }
//            dispatch(new FlowkCountCommentsJob($userLocker->blocker_id, $userLocker->locker_id, true));
//            dispatch(new CommentsCountLikesJob($userLocker->blocker_id, $userLocker->locker_id, true));
        } else {
            UserBlocker::where([
                'blocker_id'   => auth()->id(),
                'locker_id'  => $locker->id
            ])->delete();
//            dispatch(new FlowkCountCommentsJob($userLocker->blocker_id, $userLocker->locker_id, false));
//            dispatch(new CommentsCountLikesJob($userLocker->blocker_id, $userLocker->locker_id, false));
        }
    }

    public function unFollowUser(int $id): void
    {
        /** @var UserFollower $flowk */
        $follow      =  $this->repository->findOneById($id);
        if ($follow) {
            UserFollower::where([
                'follower_id'   => auth()->id(),
                'user_id'  => $follow->id
            ])->delete();
        }
    }
}
