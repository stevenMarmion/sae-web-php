<?php

declare(strict_types=1);

namespace Test\ModelsTest;

require_once __DIR__ . "/../../App/Autoloader/autoloader.php";

use App\Autoloader\Autoloader;
use App\Models\Note;

Autoloader::register();

/**
 * Classe de test pour la classe Note.
 */
class NoteTest {

    /**
     * Exécute tous les tests de la classe Note.
     */
    public function runTests() {
        $this->testConstruct();
        $this->testGetters();
        $this->testSetters();
        $this->testEquals();
    }

    /**
     * Teste la construction d'une note.
     */
    public function testConstruct() {
        $note = new Note(1, 2, 4.5);

        if ($note instanceof Note) {
            echo "<< [Test de construction : Success]\n";
        } else {
            echo "<< [Test de construction : Fail]\n";
        }
    }

    /**
     * Teste les accesseurs de la note.
     */
    public function testGetters() {
        $note = new Note(1, 2, 4.5);

        if ($note->getIdAl() === 1 && $note->getIdU() === 2 && $note->getNote() === 4.5) {
            echo "<< [Test d'accesseurs : Success]\n";
        } else {
            echo "<< [Test d'accesseurs : Fail]\n";
        }
    }

    /**
     * Teste les mutateurs de la note.
     */
    public function testSetters() {
        $note = new Note(1, 2, 4.5);

        $note->setNote(3.8);

        if ($note->getNote() === 3.8) {
            echo "<< [Test de mutateurs : Success]\n";
        } else {
            echo "<< [Test de mutateurs : Fail]\n";
        }
    }

    /**
     * Teste l'égalité entre deux notes.
     */
    public function testEquals() {
        $note1 = new Note(1, 2, 4.5);
        $note2 = new Note(1, 2, 4.5);
        $note3 = new Note(2, 2, 3.0);

        if ($note1->equals($note2) && !$note1->equals($note3)) {
            echo "<< [Test Equals : Success]\n";
        } else {
            echo "<< [Test Equals : Fail]\n";
        }
    }
}

// Exécution des tests
$noteTest = new NoteTest();
$noteTest->runTests();
?>
