#!/bin/bash

# Script Hỗ trợ Laravel Docker
# Script này cung cấp các lệnh phổ biến cho phát triển Laravel trong môi trường Docker

show_help() {
    echo "=========================================="
    echo "     Các Lệnh Hỗ trợ Laravel Docker"
    echo "=========================================="
    echo ""
    echo "Các lệnh có sẵn:"
    echo ""
    echo "  clear      - Xóa bộ nhớ cache của Laravel (view, cache, route)"
    echo "  docker     - Truy cập vào bash của container Docker (php_rubik)"
    echo "  linux      - Sửa quyền truy cập thư mục Laravel"
    echo "  install    - Cài đặt các phụ thuộc và thiết lập Laravel"
    echo "  migrate    - Chạy các migration cơ sở dữ liệu"
    echo "  controller - Tạo controller mới với các phương thức resource"
    echo "  model      - Tạo model Eloquent mới"
    echo "  composer   - Tải và cài đặt Composer"
    echo "  help       - Hiển thị menu trợ giúp này"
    echo "  exit       - Thoát khỏi script"
    echo ""
    echo "=========================================="
}

# Hiển thị menu trợ giúp khi khởi động
show_help

while true; do
    echo ""
    echo -n "Nhập lệnh: "
    read cmd

    case $cmd in
        "clear")
            echo "Đang xóa bộ nhớ cache của Laravel..."
            php artisan view:clear
            php artisan cache:clear
            php artisan route:clear
            php artisan config:clear
            echo "✅ Đã xóa tất cả cache thành công!"
            ;;

        "docker")
            echo "🐳 Đang truy cập vào bash của container Docker..."
            docker exec -it php_rubik bash
            ;;

        "linux")
            echo "🔧 Đang thiết lập quyền truy cập thư mục Laravel..."
            sudo chown -R $USER:www-data storage/
            chmod -R 775 storage/
            sudo chown -R $USER:www-data public/
            chmod -R 775 public/
            sudo chown -R $USER:www-data bootstrap/cache/
            chmod -R 775 bootstrap/cache/
            echo "✅ Đã thiết lập quyền thành công!"
            ;;

        "install")
            echo "📦 Đang cài đặt các phụ thuộc của Laravel..."
            php composer.phar install
            if [ ! -f .env ]; then
                cp .env.example .env
                echo "📄 Đã tạo tệp .env từ .env.example"
            fi
            php artisan key:generate
            echo "✅ Đã hoàn tất cài đặt Laravel!"
            ;;

        "migrate")
            echo "🗃️ Đang chạy migration cơ sở dữ liệu..."
            php artisan migrate
            echo "✅ Migration hoàn tất!"
            ;;

        "controller")
            echo -n "Nhập tên controller (ví dụ: UserController hoặc Admin/PostController): "
            read controller_name
            if [ ! -z "$controller_name" ]; then
                php artisan make:controller $controller_name --resource
                echo "✅ Đã tạo controller '$controller_name' thành công!"
            else
                echo "❌ Tên controller không được để trống!"
            fi
            ;;

        "model")
            echo -n "Nhập tên model (ví dụ: User hoặc Post): "
            read model_name
            if [ ! -z "$model_name" ]; then
                php artisan make:model $model_name
                echo "✅ Đã tạo model '$model_name' thành công!"
            else
                echo "❌ Tên model không được để trống!"
            fi
            ;;

        "composer")
            echo "📥 Đang tải và cài đặt Composer..."
            php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
            php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
            php composer-setup.php
            php -r "unlink('composer-setup.php');"
            echo "✅ Đã cài đặt Composer thành công!"
            ;;

        "help")
            show_help
            ;;

        "exit"|"quit"|"q")
            echo "👋 Tạm biệt!"
            exit 0
            ;;

        "")
            echo "⚠️ Vui lòng nhập một lệnh. Gõ 'help' để xem các lệnh có sẵn."
            ;;

        *)
            echo "❌ Lệnh không hợp lệ: '$cmd'"
            echo "💡 Gõ 'help' để xem tất cả các lệnh có sẵn."
            ;;
    esac
done