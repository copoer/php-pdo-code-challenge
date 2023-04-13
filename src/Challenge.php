<?php

namespace Otto;

class Challenge
{
    protected $pdoBuilder;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.config.php';
        $this->setPdoBuilder(new PdoBuilder($config));
    }

    /**
     * Use the PDOBuilder to retrieve all the records
     *
     * @return array
     */
    public function getRecords() 
    {
        $pdoInstance = $this->getPdoBuilder()->createPdoInstance();
        $sql = "SELECT * FROM `directors` 
                    LEFT JOIN director_businesses ON 
                        director_businesses.director_id = directors.id 
                    LEFT JOIN businesses ON 
                        businesses.id = director_businesses.business_id;
                ";
        $query = $pdoInstance->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve all the director records
     *
     * @return array
     */
    public function getDirectorRecords() 
    {
        $pdoInstance = $this->getPdoBuilder()->createPdoInstance();
        $sql = "SELECT * FROM `directors`";
        $query = $pdoInstance->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve a single director record with a given id
     *
     * @param int $id
     * @return array
     */
    public function getSingleDirectorRecord($id)
    {
        $pdoInstance = $this->getPdoBuilder()->createPdoInstance();
        $sql = "SELECT * FROM `directors` WHERE id = :inputId";
        $query = $pdoInstance->prepare($sql);
        $query->execute([':inputId' => $id]);
        return $query->fetch();
    }

    /**
     * Use the PDOBuilder to retrieve all the business records
     *
     * @return array
     */
    public function getBusinessRecords() 
    {
        $pdoInstance = $this->getPdoBuilder()->createPdoInstance();
        $sql = "SELECT * FROM `businesses`";
        $query = $pdoInstance->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve a single business record with a given id
     *
     * @param int $id
     * @return array
     */
    public function getSingleBusinessRecord($id) 
    {
        $pdoInstance = $this->getPdoBuilder()->createPdoInstance();
        $sql = "SELECT * FROM `businesses` WHERE id = :inputId";
        $query = $pdoInstance->prepare($sql);
        $query->execute([':inputId' => $id]);
        return $query->fetch();
    }

    /**
     * Use the PDOBuilder to retrieve a list of all businesses registered on a particular year
     *
     * @param int $year
     * @return array
     */
    public function getBusinessesRegisteredInYear($year)
    {
        $pdoInstance = $this->getPdoBuilder()->createPdoInstance();
        $sql = "SELECT * FROM `businesses` WHERE year(registration_date) = :inputYear";
        $query = $pdoInstance->prepare($sql);
        $query->execute([':inputYear' => $year]);
        return $query->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve the last 100 records in the directors table
     *
     * @return array
     */
    public function getLast100Records()
    {
        $pdoInstance = $this->getPdoBuilder()->createPdoInstance();
        $sql = "SELECT * FROM `directors` ORDER BY id DESC limit 100";
        $query = $pdoInstance->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * Use the PDOBuilder to retrieve a list of all business names with the director's name in a separate column.
     * The links between directors and businesses are located inside the director_businesses table.
     *
     * Your result schema should look like this;
     *
     * | business_name | director_name |
     * ---------------------------------
     * | some_company  | some_director |
     *
     * @return array
     */
    public function getBusinessNameWithDirectorFullName()
    {
        $pdoInstance = $this->getPdoBuilder()->createPdoInstance();
        $sql = "SELECT 
                CONCAT(directors.first_name, ' ', directors.last_name) as director_name, 
                businesses.name as business_name 
                FROM `directors` 
                    LEFT JOIN director_businesses ON 
                        director_businesses.director_id = directors.id 
                    LEFT JOIN businesses ON 
                        businesses.id = director_businesses.business_id;
                ";
        $query = $pdoInstance->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * @param PdoBuilder $pdoBuilder
     * @return $this
     */
    public function setPdoBuilder(PdoBuilder $pdoBuilder)
    {
        $this->pdoBuilder = $pdoBuilder;
        return $this;
    }

    /**
     * @return PdoBuilder
     */
    public function getPdoBuilder()
    {
        return $this->pdoBuilder;
    }
}
