require 'yaml'

dir = File.dirname(File.expand_path(__FILE__))

configValues = YAML.load_file("#{dir}/vagrantConfig/common.yaml")
data         = configValues['vagrantfile-local']

Vagrant.require_version ">= 1.6.3"

unless Vagrant.has_plugin?("vagrant-hostsupdater")
  raise 'Plugin required. Please run \'vagrant plugin install vagrant-hostsupdater\''
end

# @todo require vbguest?
# unless Vagrant.has_plugin?("vagrant-vbguest")
#   raise 'Plugin required. Please run \'vagrant plugin install vagrant-vbguest\''
# end

# @todo vagrant plugin install vagrant-triggers

if Vagrant.has_plugin?('vagrant-vbguest')
  class GuestAdditionsFixer < VagrantVbguest::Installers::Ubuntu
    def install(opts=nil, &block)
      super
      communicate.sudo('([ -e /opt/VBoxGuestAdditions-4.3.10 ] && sudo ln -s /opt/VBoxGuestAdditions-4.3.10/lib/VBoxGuestAdditions /usr/lib/VBoxGuestAdditions) || true')
    end
  end
end

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  # copy known_hosts from the host system
  # note that we must use Vagrant to read and write the file because the shell provisioning user
  # does not have access to the host file system
  config.vm.provision :file do |file|
    file.source      = '~/.ssh/known_hosts'
    file.destination = '/home/vagrant/.ssh/known_hosts'
  end

  # copy known_hosts for root user
    config.vm.provision :shell, :inline => [
      "echo 'Vagrantfile says: Copied known_hosts for root user'",
      "mkdir -pm 644 /root/.ssh/",
      "cp /home/vagrant/.ssh/known_hosts /root/.ssh/known_hosts"
    ] * " && "

  # copy ssh keys from the host system to vagrant and root home directories
  # both users need the keys, because the provisioning script runs as root, and when you ssh, you are vagrant
  # note that we use Vagrant to read the content of the file and echo the stdout to a file
  # instead of simply doing a bash cp on the files, because the shell provisioning user
  # does not have access to the host file system
    config.vm.provision :shell, :inline => [
      "echo 'Vagrantfile says: Copied ssh keys to vagrant and root home directories'",
      "echo -e '#{File.read("#{Dir.home}/.ssh/id_rsa")}' > '/home/vagrant/.ssh/id_rsa'",
      "echo -e '#{File.read("#{Dir.home}/.ssh/id_rsa.pub")}' > '/home/vagrant/.ssh/id_rsa.pub'",
      "echo -e '#{File.read("#{Dir.home}/.ssh/id_rsa")}' > '/root/.ssh/id_rsa'",
      "echo -e '#{File.read("#{Dir.home}/.ssh/id_rsa.pub")}' > '/root/.ssh/id_rsa.pub'"
    ] * " && "

  # set permissions and ownership for ssh keys
    config.vm.provision :shell, :inline => [
      "echo 'Vagrantfile says: Set permissions and ownership for ssh keys'",
      "chown -R vagrant:vagrant /home/vagrant/.ssh",
      "chmod 600 /home/vagrant/.ssh/id_rsa",
      "chmod 600 /root/.ssh/id_rsa"
    ] * " && "

  # Synced folders
  config.vm.synced_folder "./", "/vagrant", id: "vagrant-root", mount_options: ["dmode=777,fmode=777"]

  # Box
  config.vm.box     = "soliant/soliant-starter-box"
  config.vm.box_check_update = true

  # Hostnames
  if data['vm']['hostname'].to_s.strip.length != 0
    vhostname = "#{data['vm']['hostname']}"
  else
    vhostname = "vagrant.local"
  end
  config.vm.hostname = "#{vhostname}"
  
  subdomains = [];
  if data['hostsupdater']['vhostsubdomains']['dev'].to_s.strip.length != 0
    dev_vhostname = "#{data['hostsupdater']['vhostsubdomains']['dev']}.#{vhostname}"
  else
    dev_vhostname = "dev.#{vhostname}"
  end
  if data['hostsupdater']['vhostsubdomains']['phpmyadmin'].to_s.strip.length != 0
    phpmyadmin_vhostname = "#{data['hostsupdater']['vhostsubdomains']['phpmyadmin']}.#{vhostname}"
  else
    phpmyadmin_vhostname = "phpmyadmin.#{vhostname}"
  end
  subdomains.push(dev_vhostname)
  subdomains.push(phpmyadmin_vhostname)
  subdomains.push( "auth.#{vhostname}")
  config.hostsupdater.aliases = subdomains

  # Network
  if data['vm']['network']['private_network'].to_s != ''
    private_network_ip = "#{data['vm']['network']['private_network']}"
  end
  config.vm.network "private_network", ip: "#{private_network_ip}"
  config.ssh.forward_agent = true

  # vbguest plugin
  if Vagrant.has_plugin?('vagrant-vbguest')
    config.vbguest.installer = GuestAdditionsFixer
  end

  # Virtualbox settings:
  config.vm.provider :virtualbox do |v|
    v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    v.customize ["modifyvm", :id, "--memory", 4096]
    v.customize ["modifyvm", :id, "--cpus", 2]
  end

  # configure zend server
  config.vm.provision :shell, :path => "vagrantConfig/post.up.configure.zendserver.sh", :args => ["#{vhostname}","#{dev_vhostname}","#{phpmyadmin_vhostname}"]

  # configure starter application
  config.vm.provision :shell, :path => "vagrantConfig/post.up.configure.starterapp.sh"

  # Message
  config.vm.post_up_message = "West of House
You are standing in an open field west of a white house, with a boarded front door.
There is a small mailbox here.\n
Private Network IP: #{private_network_ip}
Login: vagrant ssh\n
App: http://#{dev_vhostname}
PhpMyAdmin: http://#{phpmyadmin_vhostname} (currently not working)
Zend Server: http://#{vhostname}:10081\n"

end
