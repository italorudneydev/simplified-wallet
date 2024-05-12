  echo -e "... ${information}Flushing All redis databases${nocolor}"
  docker-compose exec redis redis-cli flushall
