Vagrant.configure("2") do |config|
  config.vm.box = "raring64"
  config.vm.box_url = "http://cloud-images.ubuntu.com/raring/current/raring-server-cloudimg-vagrant-amd64-disk1.box"
  config.vm.hostname = "silex-rest"

  config.vm.network :private_network, ip: "192.168.2.222"
  config.ssh.forward_agent = true

  config.vm.provision :shell, :path => "Vagrantinit"
end
