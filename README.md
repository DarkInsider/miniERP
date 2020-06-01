# miniERP
installation:
back:
1)Copy .env.example and insert your credentials
2)Go to database/migrations and open 2014_10_12_000000_create_users_table file
3)Insert your password, name and email
4)Save file
5)Install dependencies (php artisan install)
front:
1)Go to src/store and open index.js file
2)Change "url" variable to address of your back server
3)Go to src/views and open Project.vue
4)Find lines that contents "http://172.17.202.252/prjFiles/" and change address
5)Install dependencies (npm install)
6)Build project (npm run build)
7)Place /dist files into your front server
