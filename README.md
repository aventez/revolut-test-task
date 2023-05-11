# Contributing guide

## 1. Cloning the repository
Firstly, clone the repository by `git-clone`

## 2. Initialize docker container
`docker-compose up -d`

## 3. Setup core container
```
1. docker exec -it web bash
2. composer install
3. php bin/console doctrine:migrations:migrate
4. docker exec -it front bash
5. npm install
6. npm run dev
7. npm run watch # (if you want to have automatic front assets rebuild)
```

## 4. App is available
App is available under:
```
 127.0.0.1:8000
```
