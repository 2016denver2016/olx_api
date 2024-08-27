# Coding Standards
General rules for spelling and naming in the project
## Contents
* [Controller](#controller)
* [Route](#route-url)
    * [Route Url](#route-url)
    * [Route Name](#route-name)
* [DataBase Related](#migration )
    * [Migration](#migration)
    * [Table](#table)
    * [Pivot Table](#pivot-table)
    * [Table Columns](#table-columns)
    * [Foreign key](#foreign-key)
    * [Primary key](#primary-key)
    * [Model](#model)
    * [Model Single relations (Has One, Belongs To)](#model-single-relations-has-one-belongs-to)
    * [Model all other relations and methods (Has Many,other)](#model-all-other-relations-and-methods-has-many-other)
* [Functions](#functions)
* [Methods in resources controller](#methods-in-resources-controller)
* [Variables](#variables)
* [Collection](#collection)
* [Object](#object)
* [Configs](#configs)
* [Traits](#traits)
* [Interface](#single-responsibility-principle)

### **Controller**
Name should be in singular form.  
Should use PascalCase.

Good | Bad
------------ | -------------
CustomerController.php | CustomersController.php

### **Route Url**
Url should be in **plural** form.  
Can use **kebab-case** if there are two words in single part For best Practice.

Good | Bad
------------ | -------------
/customers/25 | /customer/25
/customers/password-reset | /customers/password_reset
/customers/password-reset | /customers/passwordReset

### **Route Name**
Should use **snake_case** with dot notation.  
Better to use same name like in URL.

Good | Bad
------------ | -------------
->('customers.view') | ->('customers-view')
->('customers.view') | ->('customers_view')
->('customers.password_reset') | ->('customers.password.reset')
->('customers.password_reset') | ->('customers.password-reset')
->('customers.password_reset') | ->('customer-password-reset')

### **Migration**
Should use name as what you want to do with **snake_case**.

Good | Bad
------------ | -------------
2021_03_19_033513_create_customers_table.php | 2021_03_19_033513_customers.php
2021_03_19_033513_add_image_id_to_customers_table.php | 2021_03_19_033513_add_image_id_customers.php
2021_03_19_033513_drop_image_id_from_customers_table.php | 2021_03_19_033513_remove_image_id_customers.php

### **Table**
Table name must be in **plural** form.
Should use **snake_case**.

Good | Bad
------------ | -------------
customers | customer
cart_items | cartItems, CartItems, Cart_item

### **Pivot Table**
Table name must be in **singular** form.  
Should use **snake_case**  
Names should be in **alphabetical** Order.

Good | Bad
------------ | -------------
customers | customer
cart_items | cartItems, CartItems, Cart_item

### **Table Columns**
Should use **snake_case**.  
Should not use table name with column names.  
Readable name can use for better practice.

Good | Bad
------------ | -------------
first_name | user_first_name, FirstName

### **Foreign key**
Should use **snake_case**.
Should use **singular** table name with id prefix.

Good | Bad
------------ | -------------
course_id | courseId, id, courses_id, id_course

### **Primary key**
Only use name as **id**.

Good | Bad
------------ | -------------
id | custom_name_id

### **Model**
Model name must be in **singular** form.  
Should Use **PascalCase**  
Model name must be a singular form or table name.

Good | Bad
------------ | -------------
Customer | Customers, customer

### **Model Single relations (Has One, Belongs To)**
Method name must be in **singular** form.
Should Use **camalCase**

Good | Bad
------------ | -------------
studentCourse | StudentCourse ,student_course, studentCourses

### **Model all other relations and methods (Has Many, other)**
Method name must be in **plural** form.
Should use **camalCase**

Good | Bad
------------ | -------------
cartItems | CartItem, cart_item, cartItem

### **Functions**
Should use **snake_case**

Good | Bad
------------ | -------------
show_route | showRoute, ShowRoute

### **Methods in resources controller**
Should use **camelCase**  
Must use singles words related to action

Good | Bad
------------ | -------------
store | saveCustomer
show | viewCustomer
destroy | deleteCustomer
index | allCustomersPage

### **Variables**
Should use **camelCase**  
Must use readable words which are describe about value.

Good | Bad
------------ | -------------
customerMessages | $CustomerMessages, $customer_messages, $c_messages, $c_m

### **Collection**
Must describe about the value.  
Must be plural

Good | Bad
------------ | -------------
$verifiedCustomers = $customer->verified()->get() | $verified, $data, $resp, $v_c

### **Object**
Must describe about the value.  
Must be singular

Good | Bad
------------ | -------------
$verifiedCustomer = $customer->verified()->first() | $verified, $data, $resp, $v_c

### **Configs**
Should use **snake_case**
Must describe about the value.

Good | Bad
------------ | -------------
comments_enabled | CommentsEnabled, comments, c_enabled, $ce

### **Traits**
Should be adjective.

Good | Bad
------------ | -------------
Utility | UtilityTrait ,Utilities

### **Interface**
Should be adjective or a noun.

Good | Bad
------------ | -------------
Authenticable | AuthenticationInterface, Authenticate
