# BeMo Academic Consulting APIs

### Installation
1. Clone the repository
2. composer install
3. Set up .env
4. Add sqlite database file to database folder (database/database.sqlite) Or use any other database
5. php artisan migrate:fresh --seed

### API Documentation

This API supports JSON API specification. You can find the documentation here: https://jsonapi.org/

#### Base url: http://localhost:8000/api/v1

#### Authentication
All requests require authentication with an access_token parameter.
sample request:
```
GET /columns?access_token=212327873ixe
```



#### Columns:

 - Get all columns: GET /columns
 - Create a new column: POST /columns
 - Delete a column: DELETE /columns/{column}
 - Get all cards relationship for a column: GET /columns/{column}/relationships/cards
 - Update cards relationship for a column: PATCH /columns/{column}/relationships/cards
 - Get all cards for a column with all details: GET /columns/{column}/cards

#### Cards:

 - Get all cards: GET /cards. Can filter by filter[date] and filter[status] parameters
 - Create a new card: POST /cards
 - Update a card: PATCH /cards/{card}
 - Delete a card: DELETE /cards/{card}
 - Get all cards (not in JSON:API format): GET /list-cards. Can filter by 'date' and 'status' parameters

### Test
php artisan test