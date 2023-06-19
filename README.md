## PHP | Master Crud Operation From Defined Array
This is a sample PHP code just need to define table name and field array for crud 

 - Only need to define table name and field array and all crud operation work automatically
 - It will migrate table from field array 
 - Create and Edit form generate from field array 
 - Auto Form validation from field array rules
 - Store all form value based on field array
 - Listing items from table based on field array
 - Need to define array field like ~ `"api_method" => ["label" => "API method", "type" => "select", "require" => "1", "option" => ["post", "get", "put", "delete"]]`

## Overview 

#Listing page | Delete | customer

 - Define listable array and table/model
 - It will get record from table 
 - View : https://tinyurl.com/22qhhl2l

#Create/Edit customer

 - Define fillable array and table/model
 - It will automatically generate table (Migrate table) if not exist from fillable array
 - Auto Generate form from fillable array and defined rules from array
 - Validate form using defined rules from fillable array
 - Store the data in table from fillable array
 - View : https://tinyurl.com/23mzfuxz

#Table | Migration

 - It will auto migrate new table if not exist with defined field in array
 - Store all form field to table form array (No custome/other code)
 - View (table): https://tinyurl.com/2d3xwwk6