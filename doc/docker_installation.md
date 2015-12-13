# Docker installation

To run Airlines in Docker, you need to install Docker and check that the version is greater or equal to  1.0.1.

To verify your version, run:  
```sh
sudo docker --version
```

To upgrade your docker version or to install it, take a look at
[this section](https://docs.docker.com/installation/ubuntulinux/#docker-maintained-package-installation)
of the docker installation guide.

Once you are done with this, you can build your image and run your container:  
```sh
sudo docker build -t airlines .  
sudo docker run airlines  
```

It will output the IP address of your container. Now, you just have to open a browser, paste the address
and choose app.php or app_dev.php.
