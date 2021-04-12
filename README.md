# MiniSend

> Manage your mails effectively.

<p align="center">
<img src="https://i.imgur.com/lzpU4I5.png">
</p>

## Features 

- Create and send mail to anyone
- Filter your emails
- View email details
- Retry failed emails


## Installation

- `docker-compose up`
- Edit `.env` and set your database connection details
- `docker-compose exec app bash`
- (When installed via git clone or download, run `php artisan key:generate` and `php artisan jwt:secret`)
- `php artisan migrate`
- `npm install`
- To run queues run `php artisan queue:work` inside docker container

## Demos

The following videos are linked for demos:

1.  https://drive.google.com/file/d/1iCexekGWbVekx-GmKDtjNGRrw1zI-795/view?usp=sharing
2.  https://drive.google.com/file/d/1rD0CV1U1Tiaej4HlfwE-Moi9RmJLdn0u/view?usp=sharing
