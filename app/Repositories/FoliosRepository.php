<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class FoliosRepository {
    public function __construct(private PDO $db)
    {
    }

    public function getConsecutive(array $data) {
        $stmt = $this->db->prepare("
            SELECT consecutivo
            FROM folios_consecutivos
            WHERE tipo = :tipo
            AND sucursal = :sucursal
            AND ejercicio = :ejercicio
            FOR UPDATE
        ");
        
        $stmt->bindValue(':tipo', $data['type']);
        $stmt->bindValue(':sucursal', $data['branch']);
        $stmt->bindValue(':ejercicio', $data['year']);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    public function insertConsecutive(array $data) {
        try {
            $sql = "
                INSERT INTO folios_consecutivos(sucursal, tipo, ejercicio, consecutivo) 
                    VALUES(:sucursal, :tipo, :ejercicio, :consecutivo)
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':consecutivo', $data['consecutive']);
            $stmt->bindValue(':tipo', $data['type']);
            $stmt->bindValue(':sucursal', $data['branch']);
            $stmt->bindValue(':ejercicio', $data['year']);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }
    
    public function updateConsecutive(array $data) {
        try {
            $sql = "
                UPDATE folios_consecutivos SET
                    consecutivo = :consecutivo
                WHERE tipo = :tipo
                AND sucursal = :sucursal
                AND ejercicio = :ejercicio
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':consecutivo', $data['consecutive']);
            $stmt->bindValue(':tipo', $data['type']);
            $stmt->bindValue(':sucursal', $data['branch']);
            $stmt->bindValue(':ejercicio', $data['year']);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            return true;
        } catch(Exception $ex) {
            throw $ex;
        }
    }
}