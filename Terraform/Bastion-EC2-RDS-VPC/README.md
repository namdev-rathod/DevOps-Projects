# How To Connect RDS Database Sdecurely By Using Bastion Server

- **Requirements**
    - Create Custom VPC With Private and Public Subnet
    - Create Bastion Server In Public Subnet
    - Create RDS MySQL Database In Private Subnet
    - Make Sure Database Should Not Be Public Accessible
    - Open MySQL Port For Only Private Subnet & Bastion Server
    - Try To Connect Database From Bastion Server Securely
    - Install Telnet & MySQL Client To Check MySQL Connectivity From Bastion Server

- **On Bastion Server**

```
              sudo yum install telnet
              sudo yum install -y https://dev.mysql.com/get/mysql57-community-release-el7-11.noarch.rpm
              sudo yum install -y mysql-community-client
              rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql-2022

```

- **Architeccture Diagram**

![Project Details](image.png)

![Bastion Server Working](<Secure Connection For RDS.PNG>)

