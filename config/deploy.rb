set :application, 'Checkin'
set :repo_url, 'ssh://git@github.com/CorWatts/emotionalcheckin.git'

# Default branch is :master
set :branch, 'master'

# Default deploy_to directory is /var/www/my_app
set :deploy_to, '/var/www/vhosts/ilikecodeine.com'

# Default value for :scm is :git
set :scm, :git

set :keep_releases, 3

set :linked_files, %w{common/config/main-local.php common/config/params-local.php console/config/main-local.php console/config/params-local.php site/config/main-local.php site/config/params-local.php}

# Default value for :log_level is :debug
set :log_level, :debug

namespace :deploy do

  desc 'Restarting application'
  task :restart do
    on roles(:app) do
      execute "sudo service php5-fpm restart"
    end
  end

  after :published, :restart
	
  desc 'composer install'
  task :composer_install do
    on roles(:web) do
      within release_path do
        execute 'composer', 'install', '--no-dev', '--optimize-autoloader'
      end
    end
  end

  after :updated, 'deploy:composer_install'

  desc 'Migrating database'
  task :migrate do
    on roles(:app) do
      execute "cd #{release_path} && ./yii migrate --interactive=0"
    end
  end

  after :published, :migrate

  desc "Switching index.php to prod"
  task :switch_index do
    on roles(:web) do
        within release_path do
          execute 'mv', 'site/web/prod.php', 'site/web/index.php;'
        end
    end
  end

  after :migrate, 'deploy:switch_index'

  desc "Combining and minifying assets"
  task :do_assets do
    on roles(:web) do
        within release_path do
          execute './yii', 'asset', 'site/assets/assets.php', 'site/assets/assets-compressed.php;'
        end
    end
  end

  after :migrate, 'deploy:do_assets'

end
