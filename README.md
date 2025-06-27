# Coaster Monitor

## Dev environment
API is available at http://localhost:8080
### Installation
```
make up-dev
```

#### Or manually
Run docker containers
```
docker compose -f docker-compose-dev.yml up -d --build
```

Install composer dependencies
```
docker compose -f docker-compose-dev.yml exec php composer i
```

### Monitor command
```
make monitor-dev
```
or
```
docker compose -f docker-compose-dev.yml exec php php spark coaster:monitor
```

## Prod environment
API is available at http://localhost
### Installation
```
make up-prod
```
#### Or manually
Run docker containers
```
docker compose up -d --build
```

Install composer dependencies
```
docker compose exec php composer i
```

### Monitor command
```
make monitor-dev
```
or
```
docker compose exec php php spark coaster:monitor
```

## API

### POST /api/coasters

Register new coaster

| Param              | Type   | Opis                            |
|--------------------|--------|---------------------------------|
| `liczba_personelu` | int    | Number of staff                 |
| `liczba_klientow`  | int    | Daily count of customers        |
| `dl_trasy`         | int    | Route length in meters          |
| `godziny_od`       | string | Opening hour (example: `08:00`) |
| `godziny_do`       | string | Closing hour (example: `16:00`) |

### PUT /api/coasters/{coasterId}

Update existing coaster

| Param              | Type   | Opis                            |
|--------------------|--------|---------------------------------|
| `liczba_personelu` | int    | Number of staff                 |
| `liczba_klientow`  | int    | Daily count of customers        |
| `godziny_od`       | string | Opening hour (example: `08:00`) |
| `godziny_do`       | string | Closing hour (example: `16:00`) |

### POST /api/coasters/{coasterId}/wagons

Add new wagon to coaster

| Param             | Type   | Opis                            |
|-------------------|--------|---------------------------------|
| `ilosc_miejsc`    | int    | Number of seats                 |
| `predkosc_wagonu` | float  | Wagon speed (in m/s)            |

### DELETE /api/coasters/{coasterId}/wagons/{wagonId}

Delete existing wagon