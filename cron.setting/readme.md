#cron設定と反映について
cron設定をgit管理するので、crontab -e は用いないこと  
代わりに以下のように変更ファイルを反映させる  
- [開発環境]
```sh:cron設定変更、反映
$ vi  crontab_itc-dev.setting  
$ crontab crontab_itc-dev.setting  
```

- [本番環境]
```sh:cron設定変更、反映
$ vi crontab_reservel.setting  
$ crontab crontab_reservel.setting  
```
