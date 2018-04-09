# Load DSL and Setup Up Stages
require 'capistrano/setup'
set :staging, :production

set :application, 'Checkin'
set :repo_url, 'ssh://git@github.com/CorWatts/fasterscale.git'

set :keep_releases, 3

set :linked_files, %w{common/config/main-local.php common/config/params-local.php console/config/main-local.php console/config/params-local.php site/config/main-local.php site/config/params-local.php site/config/bundles-local.php .ruby-gemset .ruby-version}
set :linked_dirs, fetch(:linked_dirs, []).push('log', 'site/runtime/logs',  'site/web/charts')

# Default value for :log_level is :debug
set :log_level, :debug

namespace :deploy do

  desc 'Restarting application'
  task :restart do
    on roles(:app) do
      execute "sudo service php7.1-fpm restart"
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
          execute 'cp', 'site/web/prod.php', 'site/web/index.php;'
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

  desc "Link REVISION file to web root"
  task :link_revision_file do
    on roles(:web) do
      within "#{release_path}/site/web" do
        execute 'ln', '-s', '../../REVISION', 'REVISION;'
      end
    end
  end

  after :migrate, 'deploy:link_revision_file'

end
