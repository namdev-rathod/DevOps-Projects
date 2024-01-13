# Host Simple Website Using Secured S3 Bucket & CloudFront

> Requirements
  - Host sample application using S3 bucket and Cloudfront.
  - Make sure S3 bucket object should not be a public and it should only accessible via Cloudfront
  - Give Additional Domain Name To CloudFront
  - Configure Route53 DNS Entry 

# How To Achieve These Requirements?
 - Secure S3 Bucket
 - CloudFront
 - ACM - SSL Certificate In N.Virginia
 - Additional Domain Name For CloudFront
 - CDK Code - Typescript
 - Architecture Diagram

 ![Secured-S3-CloudFront](image.png)

 # CDK Code To Create Tech Stack
 >  **If you don't know CDK - Follow this playlist** https://www.youtube.com/playlist?list=PLqdbsgoG9hwWYlNvMJmt6rLQXaM6MoEAh

 - Find below CDK Code For This Setup

```
import * as cdk from 'aws-cdk-lib';
import { Construct } from 'constructs';
import * as s3 from 'aws-cdk-lib/aws-s3';
import * as cloudfront from 'aws-cdk-lib/aws-cloudfront';
import * as origins from 'aws-cdk-lib/aws-cloudfront-origins'
import * as route53 from 'aws-cdk-lib/aws-route53';
import * as route53Targets from 'aws-cdk-lib/aws-route53-targets';
import * as acm from 'aws-cdk-lib/aws-certificatemanager';
import * as s3deploy from 'aws-cdk-lib/aws-s3-deployment';
import * as path from 'path';

export class WebsiteHostingWithS3CloudfrontStack extends cdk.Stack {
  constructor(scope: Construct, id: string, props?: cdk.StackProps) {
    super(scope, id, props);


    # Create an S3 bucket for assets
      const assetBucket = new s3.Bucket(this, 'AssetBucket', {
        bucketName: 'test-s3-bucket-hosting-cdn',
        removalPolicy: cdk.RemovalPolicy.DESTROY,
      });

    # Deploy files to the S3 bucket during deployment
      new s3deploy.BucketDeployment(this, 'DeployFiles', {
      sources: [s3deploy.Source.asset(path.join(__dirname, 'data'))], // 'data' is a folder containing your files
      destinationBucket: assetBucket,
    });

    # Get existing ACM certificate from N. Virginia (us-east-1) region
        const certificate = acm.Certificate.fromCertificateArn(this, 'ExistingCertificate', 'arn:aws:acm:us-east-1:666930281169:certificate/48263aba-1617-451c-9fc2-0386a3023621');

   # Create a CloudFront distribution with S3 origin
      const distribution = new cloudfront.Distribution(this, 'Distribution', {
        defaultBehavior: { origin: new origins.S3Origin(assetBucket)
      },
        certificate: certificate,
        domainNames: ['app.awsguruji.net']
      });

    # Fetch existing Route 53 hosted zone
      const hostedZone = route53.HostedZone.fromLookup(this, 'ExistingHostedZone', {
        domainName: 'awsguruji.net', // Replace with your domain name
      });

    # Create DNS entry in Route 53
      new route53.ARecord(this, 'AppRecord', {
        zone: hostedZone,
        recordName: 'app',
        target: route53.RecordTarget.fromAlias(new route53Targets.CloudFrontTarget(distribution)),
      });
  }

}

```

**GitHub Repo:** https://github.com/namdev-rathod/website-hosting-with-s3-cloudfront.git

**Note:** Change the values whereever possible based on your environments.