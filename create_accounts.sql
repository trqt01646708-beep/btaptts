-- Create Admin and User Accounts
-- Run this SQL script to insert admin and user accounts into your database

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Delete existing data
DELETE FROM role_user WHERE user_id IN (SELECT id FROM users WHERE email IN ('admin@example.com', 'user@example.com'));
DELETE FROM users WHERE email IN ('admin@example.com', 'user@example.com');
DELETE FROM roles WHERE slug IN ('admin', 'user');

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Insert Admin Role
INSERT INTO roles (name, slug, description, created_at, updated_at) VALUES 
('Admin', 'admin', 'Administrator with full access', NOW(), NOW());
SET @admin_role_id = LAST_INSERT_ID();

-- Insert User Role
INSERT INTO roles (name, slug, description, created_at, updated_at) VALUES 
('User', 'user', 'Regular user', NOW(), NOW());
SET @user_role_id = LAST_INSERT_ID();

-- Insert Admin User
INSERT INTO users (name, email, password, phone, address, created_at, updated_at) VALUES 
('Admin User', 'admin@example.com', '$2y$12$RP9rJHhchEHXBlMbXy1ssusTh9u8dy/Z50gfEIMmWaLnFbH3qCHmK', '0123456789', '123 Admin Street', NOW(), NOW());
SET @admin_user_id = LAST_INSERT_ID();

-- Insert Regular User
INSERT INTO users (name, email, password, phone, address, created_at, updated_at) VALUES 
('John Doe', 'user@example.com', '$2y$12$RP9rJHhchEHXBlMbXy1ssusTh9u8dy/Z50gfEIMmWaLnFbH3qCHmK', '0987654321', '456 User Avenue', NOW(), NOW());
SET @regular_user_id = LAST_INSERT_ID();

-- Assign Admin role to admin user
INSERT INTO role_user (user_id, role_id, created_at, updated_at) VALUES 
(@admin_user_id, @admin_role_id, NOW(), NOW());

-- Assign User role to regular user
INSERT INTO role_user (user_id, role_id, created_at, updated_at) VALUES 
(@regular_user_id, @user_role_id, NOW(), NOW());

-- Note: The password hash above is for password 'password'
-- To generate a new password hash in PHP use: Hash::make('your_password')
-- Or in Laravel tinker: Hash::make('password')
