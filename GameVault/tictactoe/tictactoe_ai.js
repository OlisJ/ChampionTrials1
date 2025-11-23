
var board;
var player = "X";  // Player is X
var ai = "O";      // AI is O
var currPlayer = player;  // Player goes first
var gameOver = false;

window.onload = function() {
    setGame();
}

function setGame() {
    board = [
                [' ', ' ', ' '],
                [' ', ' ', ' '],
                [' ', ' ', ' ']
            ]

    for (let r = 0; r < 3; r++) {
        for (let c = 0; c < 3; c++) {
            let tile = document.createElement("div");
            tile.id = r.toString() + "-" + c.toString();
            tile.classList.add("tile");
            if (r == 0 || r == 1) {
                tile.classList.add("horizontal-line");
            }
            if (c == 0 || c == 1) {
                tile.classList.add("vertical-line");
            }
            tile.innerText = "";
            tile.addEventListener("click", setTile);
            document.getElementById("board").appendChild(tile);
        }
    }
}

function setTile() {
    if (gameOver) {
        return;
    }

    // Only allow player moves when it's their turn
    if (currPlayer != player) {
        return;
    }

    let coords = this.id.split("-");
    let r = parseInt(coords[0]);
    let c = parseInt(coords[1]);

    if (board[r][c] != ' ') { 
        //already taken spot
        return;
    }
    
    // Player makes move
    board[r][c] = player;
    this.innerText = player;
    
    // Check if game is over after player's move
    if (checkWinner()) {
        return;
    }
    
    // Check for tie
    if (isBoardFull()) {
        gameOver = true;
        return;
    }
    
    // Switch to AI turn
    currPlayer = ai;
    
    // AI makes move after a short delay
    setTimeout(makeAIMove, 500);
}

function makeAIMove() {
    if (gameOver) {
        return;
    }
    
    // Find best move for AI
    let bestMove = findBestMove();
    
    if (bestMove) {
        let r = bestMove.row;
        let c = bestMove.col;
        
        board[r][c] = ai;
        let tile = document.getElementById(r.toString() + "-" + c.toString());
        tile.innerText = ai;
        
        // Check if game is over after AI's move
        if (checkWinner()) {
            return;
        }
        
        // Check for tie
        if (isBoardFull()) {
            gameOver = true;
            return;
        }
        
        // Switch back to player turn
        currPlayer = player;
    }
}

function findBestMove() {
    // Check if AI can win in one move
    for (let r = 0; r < 3; r++) {
        for (let c = 0; c < 3; c++) {
            if (board[r][c] == ' ') {
                board[r][c] = ai;
                if (checkWinnerHelper(ai)) {
                    board[r][c] = ' ';
                    return { row: r, col: c };
                }
                board[r][c] = ' ';
            }
        }
    }
    
    // Check if player can win in one move, block them
    for (let r = 0; r < 3; r++) {
        for (let c = 0; c < 3; c++) {
            if (board[r][c] == ' ') {
                board[r][c] = player;
                if (checkWinnerHelper(player)) {
                    board[r][c] = ' ';
                    return { row: r, col: c };
                }
                board[r][c] = ' ';
            }
        }
    }
    
    // Take center if available
    if (board[1][1] == ' ') {
        return { row: 1, col: 1 };
    }
    
    // Take a corner if available
    let corners = [[0, 0], [0, 2], [2, 0], [2, 2]];
    for (let corner of corners) {
        if (board[corner[0]][corner[1]] == ' ') {
            return { row: corner[0], col: corner[1] };
        }
    }
    
    // Take any available spot
    for (let r = 0; r < 3; r++) {
        for (let c = 0; c < 3; c++) {
            if (board[r][c] == ' ') {
                return { row: r, col: c };
            }
        }
    }
    
    return null;
}

function isBoardFull() {
    for (let r = 0; r < 3; r++) {
        for (let c = 0; c < 3; c++) {
            if (board[r][c] == ' ') {
                return false;
            }
        }
    }
    return true;
}

function checkWinner() {
    let winner = null;
    
    // Check if player won
    if (checkWinnerHelper(player)) {
        winner = player;
    }
    // Check if AI won
    else if (checkWinnerHelper(ai)) {
        winner = ai;
    }
    
    if (winner) {
        highlightWinner(winner);
        gameOver = true;
        return true;
    }
    
    return false;
}

function checkWinnerHelper(playerSymbol) {
    //horizontally, check 3 rows
    for (let r = 0; r < 3; r++) {
        if (board[r][0] == playerSymbol && board[r][1] == playerSymbol && board[r][2] == playerSymbol) {
            return true;
        }
    }

    //vertically, check 3 columns
    for (let c = 0; c < 3; c++) {
        if (board[0][c] == playerSymbol && board[1][c] == playerSymbol && board[2][c] == playerSymbol) {
            return true;
        }
    }

    //diagonally
    if (board[0][0] == playerSymbol && board[1][1] == playerSymbol && board[2][2] == playerSymbol) {
        return true;
    }

    //anti-diagonally
    if (board[0][2] == playerSymbol && board[1][1] == playerSymbol && board[2][0] == playerSymbol) {
        return true;
    }
    
    return false;
}

function highlightWinner(winner) {
    //horizontally, check 3 rows
    for (let r = 0; r < 3; r++) {
        if (board[r][0] == winner && board[r][1] == winner && board[r][2] == winner) {
            for (let i = 0; i < 3; i++) {
                let tile = document.getElementById(r.toString() + "-" + i.toString());
                tile.classList.add("winner");
            }
            return;
        }
    }

    //vertically, check 3 columns
    for (let c = 0; c < 3; c++) {
        if (board[0][c] == winner && board[1][c] == winner && board[2][c] == winner) {
            for (let i = 0; i < 3; i++) {
                let tile = document.getElementById(i.toString() + "-" + c.toString());                
                tile.classList.add("winner");
            }
            return;
        }
    }

    //diagonally
    if (board[0][0] == winner && board[1][1] == winner && board[2][2] == winner) {
        for (let i = 0; i < 3; i++) {
            let tile = document.getElementById(i.toString() + "-" + i.toString());                
            tile.classList.add("winner");
        }
        return;
    }

    //anti-diagonally
    if (board[0][2] == winner && board[1][1] == winner && board[2][0] == winner) {
        let tile = document.getElementById("0-2");                
        tile.classList.add("winner");

        tile = document.getElementById("1-1");                
        tile.classList.add("winner");

        tile = document.getElementById("2-0");                
        tile.classList.add("winner");
    }
}

