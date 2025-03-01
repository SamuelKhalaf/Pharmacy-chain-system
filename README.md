# Pharmacy Management System

## Overview

The **Pharmacy Management System** is a web-based application built using Laravel to manage pharmacy branches, inventory, and transfer requests. The system allows multiple branches to track product availability, request transfers between branches, and ensure stock is well managed.

## Features

- **Multi-Branch Support:** Allows pharmacies to manage multiple branches with independent inventories.
- **Product Inventory Management:** Track stock levels for each product across different branches.
- **Transfer Requests:** Request and approve product transfers between branches.
- **Request Approval System:** Accept or reject transfer requests based on inventory availability.
- **Status Management:** Each transfer request has statuses (`pending`, `accepted`, `rejected`, `cancelled`).
- **Real-Time Updates:** Uses AJAX to update the UI without refreshing the page.
- **Role-Based Access Control:** Restricts actions based on user roles (Super Admin, Admin , Manager).
- **API Support:** Provides endpoints for external integration.

## Technologies Used

### **Backend** (Laravel)

- **Laravel 10**: The main framework used for the backend.
- **Laravel Facades & Service Layer**: To maintain clean and scalable code architecture.
- **Laravel Repositories & Services Pattern**: Ensures better separation of concerns.
- **Laravel API Routes**: Provides a RESTful API for the system.
- **Database Migrations & Seeders**: To manage database schema and initial data.
- **Authentication & Middleware**: Secures routes and restricts unauthorized access.
- **Laravel Validation**: Ensures data integrity before processing requests.

### **Frontend**

- **Blade Templates**: For structuring the frontend views.
- **AJAX & jQuery**: For handling dynamic requests without reloading the page.
- **DataTables**: For displaying and filtering transfer requests efficiently.
- **Bootstrap**: For responsive UI design.

### **Database**

- **MySQL**: Relational database to store pharmacy and inventory data.
- **Eloquent ORM**: Used for querying and managing the database efficiently.

## Laravel Components Used

- **Controller & Service Layer**:

    - `TransferRequestController`: Handles transfer requests.
    - `BranchInventoryController`: Manages inventory per branch.
    - `AuthController`: Manages user authentication.

- **Facades & Repositories**:

    - `DB::table()` for custom database queries.
    - `TransferRequestService` for handling business logic.
    - `BranchInventoryService` for managing stock operations.

- **Middleware**:

    - `AuthMiddleware`: Protects routes requiring authentication.
    - `RoleMiddleware`: Restricts access based on user roles.


