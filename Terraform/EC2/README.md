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