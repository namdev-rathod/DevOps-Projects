# Create First EC2 By Using Terraform

- Create simple EC2 instance with user data script to install Nginx web server

```
provider "aws" {
  region = "ap-south-1" # Replace with your desired AWS region
}


resource "aws_instance" "terraform_example_instance" {
  ami           = "ami-05552d2dcf89c9b24" # Replace with your desired Linux AMI ID
  instance_type = "t3.micro"
  key_name      = "AWS-EC2-Test-Server-Key" # Replace with your EC2 key pair name
  vpc_security_group_ids = ["sg-0cf5ad9a0f3b27517"]
  subnet_id     = "subnet-0a68e97507e0c7fae"
  count         = 1

  tags = {
    Name = "ec2-instance"
  }

    user_data = <<-EOF
              #!/bin/bash
              yum update -y
              yum install nginx -y
              systemctl start nginx
              echo "<html><body><h1>Hello World from Terraform EC2</h1></body></html>" > /usr/share/nginx/html/index.html
              systemctl enable nginx
              EOF

}

```

# Create EC2 Instance With Load Balancer, Route53 & SSL Certificate
- Create EC2 instance with t3.micro
- Install Nginx Web Server By Using User Data Script
- Create Load Balancer with Target Group
- Register EC2 Instance With Target Group
- Attach Target Group To Load Balancer
- Attach SSL Certificate On Load Balancer
- Domain name should be https://web.awsguruji.net


```
provider "aws" {
  region = "ap-south-1" # Replace with your desired AWS region
}


# Specify the existing VPC ID and subnet ID where you want to launch the EC2 instance
data "aws_vpc" "existing_vpc" {
  id = "vpc-0e0e20c7600fd9b59" # Replace with your existing VPC ID
}

# Define variables (replace with your actual values)
# variable "vpc_id" {
#  type    = string
#  default = "vpc-0e0e20c7600fd9b59"
#}

variable "subnet_id_1" {
  type    = string
  default = "subnet-0a68e97507e0c7fae"
}

variable "subnet_id_2" {
  type    = string
  default = "subnet-0148d982d214587dd"
}

# Existing ACM SSL Certificate ARN
variable "ssl_certificate_arn" {
    type = string
    default = "arn:aws:acm:ap-south-1:666930281169:certificate/626953f4-818d-4c59-ac5c-39e64e0bab49"
    }

variable "security_group_id" {
  type    = string
  default = "sg-0cf5ad9a0f3b27517"
}

variable "key_pair_name" {
  type    = string
  default = "AWS-EC2-Test-Server-Key"
}

# Create EC2 instance
resource "aws_instance" "terraform_ec2_instance" {
  ami           = "ami-05552d2dcf89c9b24" # Replace with your desired Linux AMI ID
  instance_type = "t3.micro"
  key_name      = var.key_pair_name
  vpc_security_group_ids = [var.security_group_id]
  subnet_id              = var.subnet_id_1

    user_data = <<-EOF
              #!/bin/bash
              sudo yum update -y
              sudo yum install -y nginx
              echo "<html><body><h1><b>Hello, Terraform World!</b></h1></body></html>" > /usr/share/nginx/html/index.html
              sudo service nginx start
              EOF

  tags = {
    Name = "ec2-instance"
  }
}

# Create a public-facing Application Load Balancer
resource "aws_lb" "example_lb" {
  name               = "example-lb"
  internal           = false
  load_balancer_type = "application"
  security_groups    = [var.security_group_id]
  subnets = [var.subnet_id_1, var.subnet_id_2]
  enable_deletion_protection = false
}

# Create a target group
resource "aws_lb_target_group" "example_target_group" {
  name        = "example-target-group"
  port        = 80
  protocol    = "HTTP"
  vpc_id      = data.aws_vpc.existing_vpc.id
  health_check {
    path = "/"
  }
}

# Attach the EC2 instance to the target group
resource "aws_lb_target_group_attachment" "example_target_attachment" {
  target_group_arn = aws_lb_target_group.example_target_group.arn
  target_id        = aws_instance.terraform_ec2_instance.id
}

# Attach the target group to the load balancer
resource "aws_lb_listener" "example_lb_listener" {
  load_balancer_arn = aws_lb.example_lb.arn
  port              = 80
  protocol          = "HTTP"

  default_action {
    type             = "forward"
    target_group_arn = aws_lb_target_group.example_target_group.arn
  }
}



# Create HTTPS Listener using ACM SSL Certificate
resource "aws_lb_listener" "example_listener" {
  load_balancer_arn = aws_lb.example_lb.arn
  port              = 443
  protocol          = "HTTPS"

  default_action {
    type             = "forward"
    target_group_arn = aws_lb_target_group.example_target_group.arn
  }

  ssl_policy      = "ELBSecurityPolicy-2016-08"
  certificate_arn = var.ssl_certificate_arn
}


# DNS entry in Route 53 for the load balancer
resource "aws_route53_record" "example_dns" {
  zone_id = "Z0808885ROJ6FHJCCCXN" # Replace with your Route 53 hosted zone ID
  name    = "web.awsguruji.net"    # Replace with your desired domain name
  type    = "A"
  alias {
    name                   = aws_lb.example_lb.dns_name
    zone_id                = aws_lb.example_lb.zone_id
    evaluate_target_health = true
  }
}

```