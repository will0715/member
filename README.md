# 會員平台 - web service

## Author - Poyi.huang

## required

-   docker
-   docker-compose
-   postgresql

## 安裝方式

1. composer install
2. php artisan key:generate
3. php artisan db:seed

## 新客戶

php artisan customer:add test --account=w67890w67890@gmail.com --password=123123

## 功能

[x] 會員功能
[x] 會員功能
[x] 店家功能
[x] 累點規則
[x] 兌點規則
[x] 會員等級
[x] 儲值卡
[x] 累點功能
[x] 兌點功能
[x] 交易資料
[x] 新增交易
[x] 新增交易(自動累點)
[] 會員等級自動升級
[] 促銷／折扣
[] 權限管理
[] 會員Ｃ端
[] 規則適用等級改為一對多
[] 會員群組
[] 通知管理(line、sms、email)
[] 電子優惠卷
[] 寄杯卷
[] 管理 (slack notification)
