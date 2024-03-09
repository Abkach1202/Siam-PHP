CREATE TABLE IF NOT EXISTS User (
  username TEXT PRIMARY KEY,
  first_name TEXT,
  last_name TEXT,
  email TEXT,
  password TEXT,
  is_admin BOOLEAN,
  registration_date DATE
);

CREATE TABLE IF NOT EXISTS Game (
  game_ID INTEGER PRIMARY KEY AUTOINCREMENT,
  board TEXT,
  last_move TEXT,
  is_over BOOLEAN,
  player1 TEXT,
  player2 TEXT,
  winner TEXT,
  active_player TEXT,
  launcher TEXT,
  launch_date DATE,
  FOREIGN KEY (player1) REFERENCES User(username),
  FOREIGN KEY (player2) REFERENCES User(username),
  FOREIGN KEY (winner) REFERENCES User(username),
  FOREIGN KEY (active_player) REFERENCES User(username),
  FOREIGN KEY (launcher) REFERENCES User(username)
);