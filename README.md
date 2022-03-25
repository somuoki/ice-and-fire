# ice-and-fire api
A Game of thrones Books Api

## Installation
Clone or download the repository

`https://github.com/somuoki/ice-and-fire.git`

After cloning move into the folder and install dependancies

```
cd ice-and-fire
composer install
```

Run the following command to create an apache server instance

` php -S localhost:8000 -t public`

## Working Instance

https://topup-ice.herokuapp.com/api/

##Api Consumer app

https://gotconsumer.herokuapp.com/

https://github.com/somuoki/got-api-consumer

## Endpoints

The Api uses a total of 4 endpoints in which you can pass an array of parameters
```
getBooks | https://topup-ice.herokuapp.com/api/books
getHouses | https://topup-ice.herokuapp.com/api/houses
getCharacters | https://topup-ice.herokuapp.com/characters
```

### Usage
To access any of the endpoints send request to the corresponding url e.g.

`https://topup-ice.herokuapp.com/api/books`

Parameters

If you want a list of all items within a category i.e books, houses nad characters there is no need of passing any parameters

If you want a single item from any of the categories using an id just add the id to the url

`https://topup-ice.herokuapp.com/api/books/1`

The Common parameter is name can be used to get a specific item

```
array(
    'name' => 'Jon Snow' //gets the item in the category by name * should not be used with id
    )
```

### Book Parameters

```
array(
    'fromRealeaseDate' => 1996,
    'toReleaseDate' => 2000,
    'page' => 2, // get page number
    'pageSize' => 24 //get 24 books
    )
```

### Character Parameters

```
array(
    'gender' => 'male',
    'culture' => 'Braavosi',
    'isAlive' => TRUE, 
    'page' => 2,
    'pageSize' => 24
    )
```

### House Parameters

```
array(
    'region' => 'The Westerlands',
    'words' => '',
    'hasWords' => ''TRUE'',
    'hasTitles' => ''TRUE'',
    'hasSeats' => ''TRUE'',
    'hasDiedOut' => ''TRUE'',
    'hasAncestralWeapons' => ''TRUE'',
    page' => 2,
    'pageSize' => 24 //get 24 books
    )
```
