<?php

namespace App\Models;

require_once __DIR__ . '/../Autoloader/autoloader.php';

use \App\Autoloader\Autoloader;

Autoloader::register();

/**
 * Class Note
 *
 * Représente une note associée à un utilisateur et à un album.
 */
class Note
{
    /**
     * @var int L'identifiant de l'album.
     */
    private int $idAl;

    /**
     * @var int L'identifiant de l'utilisateur qui a attribué la note.
     */
    private int $idU;

    /**
     * @var float La note attribuée à l'album (entre 0 et 5).
     */
    private float $note;

    /**
     * Constructeur de la classe Note.
     *
     * @param int   $idAl L'identifiant de l'album.
     * @param int   $idU  L'identifiant de l'utilisateur qui a attribué la note.
     * @param float $note La note attribuée à l'album (entre 0 et 5).
     */
    public function __construct($idAl, $idU, $note)
    {
        $this->idAl = $idAl;
        $this->idU = $idU;
        $this->note = $note;
    }

    /**
     * Obtenir l'identifiant de l'album associé à la note.
     *
     * @return int L'identifiant de l'album.
     */
    public function getIdAl()
    {
        return $this->idAl;
    }

    /**
     * Obtenir l'identifiant de l'utilisateur qui a attribué la note.
     *
     * @return int L'identifiant de l'utilisateur.
     */
    public function getIdU()
    {
        return $this->idU;
    }

    /**
     * Obtenir la note attribuée à l'album.
     *
     * @return float La note attribuée (entre 0 et 5).
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Définir la note attribuée à l'album.
     *
     * @param float $note La nouvelle note attribuée (entre 0 et 5).
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Vérifier l'égalité avec une autre note.
     *
     * @param Note $other L'autre note à comparer.
     *
     * @return bool Retourne true si les notes sont égales, sinon false.
     */
    public function equals(Note $other)
    {
        return $this->idAl === $other->idAl && $this->idU === $other->idU;
    }

}

?>
