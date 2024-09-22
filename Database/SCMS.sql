/*The user/student table that is updated when the profle is updated.........*/
CREATE TABLE users_signup (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    matric_no VARCHAR(20) NOT NULL,
    password CHAR(255) NOT NULL,
    reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(100) DEFAULT NULL,
    department VARCHAR(50) DEFAULT NULL
);

/*The admin database, tho the data are entered manually because an admin can't create account*/

CREATE TABLE admin_signup (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(100) NOT NULL,
    password CHAR(255) NOT NULL,
    reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);


/*The sub-admin database - the info is stored onced the admin creates the sub-admin and deleted once the admin deletes, the informaton is used to authenticate the sub-admin login*/

CREATE TABLE subadmin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password CHAR(255) NOT NULL,
    reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    department VARCHAR(50) NOT NULL
);

/*The compaint table that tracks all complaints from user and also updates onces the admin or sub-admin takes action on the complaint*/

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    complaint_type VARCHAR(50) NOT NULL,
    natureOfComplaint TEXT NOT NULL,
    complaint_file VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) NOT NULL DEFAULT 'not processed',
    fullname VARCHAR(100) DEFAULT NULL,
    remark TEXT DEFAULT NULL,
    forwarded_to VARCHAR(100) DEFAULT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users_signup(id)
);




