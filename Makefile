.PHONY: start stop reset logs shell-back shell-db

start:
	cp telemed-backend/.env.docker telemed-backend/.env
	docker-compose up -d --build
	@echo "Waiting for database to be ready (15s)..."
	@sleep 15
	docker exec telemed_backend php artisan migrate --seed --force
	docker exec telemed_backend php artisan storage:link
	@echo ""
	@echo "==================================="
	@echo "  TeleMed is RUNNING!"
	@echo "==================================="
	@echo "  Frontend: http://localhost:5173"
	@echo "  Backend:  http://localhost:8000"
	@echo "-----------------------------------"
	@echo "  admin@telemed.ma   / admin123"
	@echo "  arrami@telemed.ma  / doctor123"
	@echo "  jean@telemed.ma    / patient123"
	@echo "==================================="

stop:
	docker-compose down

reset:
	docker-compose down -v
	cp telemed-backend/.env.docker telemed-backend/.env
	docker-compose up -d --build
	@sleep 20
	docker exec telemed_backend php artisan migrate:fresh --seed --force
	docker exec telemed_backend php artisan storage:link

logs:
	docker-compose logs -f

shell-back:
	docker exec -it telemed_backend bash

shell-db:
	docker exec -it telemed_db mysql -u telemed -ptelemed123 telemed_db
