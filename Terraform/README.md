# How To Setup Terraform Project On Local Windows Machine
- Download Terraform From https://developer.hashicorp.com/terraform/install Its depend on OS type
- For Linux

```
sudo yum install -y yum-utils
sudo yum-config-manager --add-repo https://rpm.releases.hashicorp.com/RHEL/hashicorp.repo
sudo yum -y install terraform

```
- For Mac

```
brew tap hashicorp/tap
brew install hashicorp/tap/terraform

```

- For Windows https://releases.hashicorp.com/terraform/1.7.1/terraform_1.7.1_windows_amd64.zip
- extract it on download folder
- Copy terraform folder into C:\Program Files\terraform
- It should looks like below
![Terraform](image.png)

- Open Control Panel
- Click On System

![System](image-1.png)

- Click On Advanced System Setting

![System](image-2.png)

- Click On Environment Variables

![Environment](image-3.png)

