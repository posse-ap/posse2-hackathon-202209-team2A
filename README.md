# ハッカソン202109

## ビルド

ディレクトリに移動して以下のコマンドを実行してください

```bash
docker-compose build --no-cache
docker-compose up -d
```

## 動作確認
### ログイン
#### ブラウザで `http://localhost` にアクセスしてください。
![image](https://user-images.githubusercontent.com/94669039/189110868-00a9a465-2c77-4f95-b83a-89c76365ee8b.png)

#### ログインページが表示されます。
![image](https://user-images.githubusercontent.com/94669039/189111328-c962b4d6-f93f-4d9c-864c-0bd168862239.png)

#### 管理者権限があるアカウントでログインします。
管理者権限があるアカウントのメールアドレスとパスワードは以下の通りです。
実際に入力してログインしてください。
- メールアドレス ->user@posse.com
- パスワード ->pass
<br>

![image](https://user-images.githubusercontent.com/94669039/189111749-67291758-1b18-46ad-a97f-e381f26b431f.png)

#### ログイン完了後のトップページ
管理者権限があるので、ヘッダーの右の部分に管理画面へ進むためのボタンがあります。
それを押して、管理画面に移動します。
<br>
![image](https://user-images.githubusercontent.com/94669039/189113786-b65c8ca2-45e8-48cf-874e-1055830e5f07.png)

### 管理画面からイベント登録
#### 管理画面
イベント登録するので、「イベント追加」ボタンを押します。
<br>
![image](https://user-images.githubusercontent.com/94669039/189113961-ad037e1d-e96a-4629-94e0-8b5efb2a7362.png)

#### イベント追加ページ
ここで、
- イベント名
- 開始日時
- 終了日時
- イベント詳細
<br>
を入力して「送信」ボタンを押すと、イベントが追加されます。
<br>
<img width="342" alt="image" src="https://user-images.githubusercontent.com/94669039/189119132-c83e2924-3cce-4511-a932-a59b05411a45.png">

#### 管理画面でイベントが追加されたことを確認
先ほどはなかった「折り紙会」が表示されています。
<br>
![image](https://user-images.githubusercontent.com/94669039/189115745-fa263246-bd62-46c4-8bd3-ecd84b842d5b.png)

#### ユーザー画面でもイベントが追加されたことを確認
先ほどはなかった「折り紙会」が表示されています。
<br>
![image](https://user-images.githubusercontent.com/94669039/189115884-f6b04375-5505-4104-86f0-849bdd06df22.png)

### ユーザー画面でイベント参加登録

#### ユーザー画面のイベント詳細画面
ここで、参加または不参加の登録をすることができます。
<br>
![image](https://user-images.githubusercontent.com/94669039/189116071-bf0e62b3-fe1d-462e-b834-a18fefb79663.png)

#### イベント参加登録完了
フィルターのボタンを押して移動すると、参加登録が完了していることが確認できます。
<br>
![image](https://user-images.githubusercontent.com/94669039/189116326-3bd727e0-bfcb-413c-a3a2-10f43a935935.png)
<br>
![image](https://user-images.githubusercontent.com/94669039/189117713-9095d26e-5672-43ec-8e9f-38394b162ea0.png)


## メール送信サンプルについて

メール送信
ブラウザで `http://localhost/mailtest.php` にアクセスしてください、テストメールが送信されます

メール受信
ブラウザで `http://localhost:8025/` にアクセスしてください、メールボックスが表示されます
