CREATE TABLE IF NOT EXISTS User (
  User_ID INTEGER PRIMARY KEY AUTOINCREMENT,
  First_name VARCHAR(30),
  Last_name VARCHAR(30),
  Username VARCHAR(30),
  Email VARCHAR(50),
  Password VARCHAR(30),
  Is_admin BOOLEAN,
  Registration_date DATE
);

CREATE TABLE IF NOT EXISTS Game (
  Game_ID INTEGER PRIMARY KEY AUTOINCREMENT,
  Board TEXT,
  Last_move VARCHAR(10),
  Is_over BOOLEAN,
  Player1_ID INTEGER,
  Player2_ID INTEGER,
  Winner_ID INTEGER,
  Active_player_ID INTEGER,
  Launcher_ID INTEGER,
  Launch_Date DATE,
  FOREIGN KEY (Player1_ID) REFERENCES User(User_ID),
  FOREIGN KEY (Player2_ID) REFERENCES User(User_ID),
  FOREIGN KEY (Winner_ID) REFERENCES User(User_ID),
  FOREIGN KEY (Active_player_ID) REFERENCES User(User_ID),
  FOREIGN KEY (Launcher_ID) REFERENCES User(User_ID)
);