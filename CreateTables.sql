use sdev;

create table Products(
	ProductID varchar(30) primary key,
	Type varchar(30),
	Price int);

create table Customers(
	CustomerID varchar(30) primary key,
	FirstName varchar(30),
	LastName varchar(30),
	EmailAddress varchar(60));