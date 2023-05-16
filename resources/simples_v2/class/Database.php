<?php
//Abre a conexao com MySQL
function DBConnect($db = null){

	$link = @mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), $db ?:env('DB_DATABASE') ) or die(mysqli_connect_error());
	mysqli_set_charset($link, 'utf8mb4') or die(mysqli_error($link));
	return $link;
}

//Fecha a conexao com MySQL
function DBClose($link) {
	@mysqli_close($link) or die(mysqli_error($link));
}

//Protege contra SQL Injection
function DBEscape($db, $data) {
	$link = DBConnect($db);
	if (!is_array($data)) {
        $data = mysqli_real_escape_string($link, $data);
	} else {
		$arr = $data;
		foreach ($arr as $key => $value) {
            if($value !== NULL){
                $value = mysqli_real_escape_string($link, $value);
            }
			$key = mysqli_real_escape_string($link, $key);
			$data[$key] = $value;
        }
	}
	DBClose($link);
	return $data;
}

//Executa querys
function DBExecute($db, $query, $insertID = false) {
	$link = DBConnect($db);
	$result = @mysqli_query($link, $query) or die(mysqli_error($link));
	if ($insertID) {
		$result = mysqli_insert_id($link);
	}
	DBClose($link);
	return $result;
}

//Grava registros
function DBCreate($db, $table, array $data, $insertID = false) {
	$data = DBEscape($db, $data);
	$fields = implode(', ', array_keys($data));
    foreach ($data as $key => $value) {
		if ($value === NULL) {
			$values[] = "NULL";
		} else {
            $values[] = "'{$value}'";

		}
	}
	$values = implode(', ', $values);
	$query = "INSERT INTO {$table} ( {$fields} ) VALUES ( {$values} )";
	return DBExecute($db, $query, $insertID);
}

//Le registros
function DBRead($db, $table, $params = null, $fields = '*'){
	$params = ($params) ? " {$params}" : null;
	$query = "SELECT {$fields} FROM {$table}{$params}";
	$result = DBExecute($db, $query);
	if(!mysqli_num_rows($result)){
		return false;
	}else{
		while ($res = mysqli_fetch_assoc($result)){
			$data[] = $res;
		}
		return $data;
	}
}
//Altera registros
function DBUpdate($db, $table, array $data, $where = null, $insertID = false) {
	$data = DBEscape($db, $data);
	foreach ($data as $key => $value) {
		if ($value === NULL) {
            $fields[] = "{$key} = NULL";
		} else {
			$fields[] = "{$key} = '{$value}'";
		}
	}
	$fields = implode(', ', $fields);
	$where = ($where) ? " WHERE {$where}" : null;
	$query = "UPDATE {$table} SET {$fields}{$where}";
	return DBExecute($db, $query, $insertID);
}

//Deleta registros
function DBDelete($db, $table, $where = null) {
	$where = ($where) ? " WHERE {$where}" : null;
	$query = "DELETE FROM {$table}{$where}";
	return DBExecute($db, $query);
}

########################## BEGIN ####################################

//inicia transaçao com BEGIN TRANSACTION (abrir conexao antes)
function DBBegin($link){
    $query = "START TRANSACTION";
    return DBExecuteTransaction($link, $query);
}

//Executa querys com BEGIN TRANSACTION
function DBExecuteTransaction($link, $query, $insertID = false) {
	$result = @mysqli_query($link, $query) or die(mysqli_error($link));
	if ($insertID) {
		$result = mysqli_insert_id($link);
	}
	return $result;
}

//Protege contra SQL Injection com BEGIN TRANSACTION
function DBEscapeTransaction($link, $data) {
	if (!is_array($data)) {
        $data = mysqli_real_escape_string($link, $data);
	} else {
		$arr = $data;
		foreach ($arr as $key => $value) {
            if($value !== NULL){
                $value = mysqli_real_escape_string($link, $value);
            }
			$key = mysqli_real_escape_string($link, $key);
			$data[$key] = $value;
        }
	}
	return $data;
}

//Grava registros com BEGIN TRANSACTION
function DBCreateTransaction($link, $table, array $data, $insertID = false) {
	$data = DBEscapeTransaction($link, $data);
    $fields = implode(', ', array_keys($data));
	foreach ($data as $key => $value) {
		if ($value === NULL) {
            $values[] = "NULL";
		} else {
			$values[] = "'{$value}'";
		}
	}
	$values = implode(', ', $values);
    $query = "INSERT INTO {$table} ( {$fields} ) VALUES ( {$values} )";
	return DBExecuteTransaction($link, $query, $insertID);
}

//Le registros com BEGIN TRANSACTION
function DBReadTransaction($link, $table, $params = null, $fields = '*'){
	$params = ($params) ? " {$params}" : null;
	$query = "SELECT {$fields} FROM {$table}{$params}";
	$result = DBExecuteTransaction($link, $query);
	if(!mysqli_num_rows($result)){
		return false;
	}else{
		while ($res = mysqli_fetch_assoc($result)){
			$data[] = $res;
		}
		return $data;
	}
}

//Altera registros com BEGIN TRANSACTION
function DBUpdateTransaction($link, $table, array $data, $where = null, $insertID = false) {
	$data = DBEscapeTransaction($link, $data);
	foreach ($data as $key => $value) {
		if ($value === NULL) {
            $fields[] = "{$key} = NULL";
		} else {
			$fields[] = "{$key} = '{$value}'";
		}
	}
	$fields = implode(', ', $fields);
	$where = ($where) ? " WHERE {$where}" : null;
	$query = "UPDATE {$table} SET {$fields}{$where}";
	return DBExecuteTransaction($link, $query, $insertID);
}

//Deleta registros com BEGIN TRANSACTION
function DBDeleteTransaction($link, $table, $where = null) {
	$where = ($where) ? " WHERE {$where}" : null;
	$query = "DELETE FROM {$table}{$where}";
	return DBExecuteTransaction($link, $query);
}

//encerra transacao com COMMIT
function DBCommit($link){
    $query = "COMMIT";
    DBExecuteTransaction($link, $query);
    DBClose($link);
}

?>