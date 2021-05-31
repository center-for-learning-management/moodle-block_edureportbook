<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   block_edureportbook
 * @copyright 2019 Digital Education Society (http://www.dibig.at)
 * @author    Robert Schrenk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Mitteilungsheft-Assistent';
$string['privacy:metadata'] = 'Dieses Plugin speichert keine personenbezogenen Daten';
$string['edureportbook:addinstance'] = 'Mitteilungsheft einrichten';
$string['edureportbook:myaddinstance'] = 'Mitteilungsheft einrichten';

$string['assignstudents'] = 'Erziehungsberechtigte zuordnen';
$string['assignstudents:description'] = 'Auf der linken Seite sehen Sie die SchülerInnen Ihres Kurses. Ordnen Sie bitte beliebig viele Erziehungsberechtigte zu!';
$string['assignstudents:generalgroup:description'] = 'Die folgende Gruppe ist für den allgemeinen Austausch aller Personen dieses Mitteilungshefts gedacht. Es wird daher empfohlen alle Personen hier auszuwählen, aber natürlich können Sie auch eine eigene Auswahl treffen!';

$string['assistant_descr_about'] = '
    <p>
        Dieser Assistent hilft Ihnen bei der Einrichtung eines digitalen Mitteilungshefts.
        Für die Kommunikation werden zwei Foren verwendet. Eines dieser Foren wird
        für alle Nutzer/innen für einen allgemeinen Austausch zugänglich sein. Im
        zweiten Forum wird es für jedes Kind eine separate Gruppe geben, die eine
        vertrauliche Kommunikation mit den Erziehungsberechtigten des jeweiligen
        Kindes erlaubt.
    </p>
    <p>
        Mit diesem Mitteilungsheft haben Sie also die gesamte Kommunikation im Blick,
        und trotzdem gibt es einen privaten Kommunikationskanal für sensible Informationen!
    </p>
    <p>
        Alle beteiligten Personen erhalten Benachrichtigungen standardmäßig an die im
        Nutzerprofil hinterlegte E-Mailadresse. Zusätzlich ist die Verwendung der Moodle
        Mobile App möglich, über die dann sofortige Benachrichtigungen am
        Mobilgerät der betreffenden Empfänger/innen empfangen werden können.
    </p>
    <p>
        Mehr Informationen zur Moodle Mobile App erhalten Sie unter
        <a href="https://moodle.com/de/app/" target="_blank">https://moodle.com/de/app/</a>!
    </p>';
$string['assistant_finish_btn'] = 'Assistenten beenden';
$string['assistant_finish_done'] = '
    <p>
        Wunderbar, alle Schritte dieses Assistenten wurden ausgeführt. Sie können
        diesen Assistenten nun beenden. Hoffentlich war dieser Assistent hilfreich für Sie.
        Jederzeit können Sie ihn reaktivieren, indem Sie den Block zur Kursseite
        wieder hinzufügen.
    </p>';

$string['assistant_invalid_stage'] = 'Ungültiger Fortschritt angefordert.';

$string['assistant_stage_about'] = 'Über';
$string['assistant_stage_users'] = 'Teilnehmer/innen';
$string['assistant_stage_protection'] = 'Privatsphäre';
$string['assistant_stage_finish'] = 'Abschluss';

$string['assistant_users_enrolments'] = 'Teilnehmer/innen einschreiben';
$string['assistant_users_enrolments_description'] = 'Sie können jede Einschreibemethode verwenden, die Ihr Moodle-System ermöglicht. Bitte stellen Sie sicher, dass Erziehungsberechtigten die Rolle "{$a->rolename_legalguardian}" und Schüler/innen die Rolle "{$a->rolename_student}" zugewiesen wird!';
$string['assistant_users_relation'] = 'Verbindung zwischen Schüler/innen und Erziehungsberechtigten';
$string['assistant_users_relation_description'] = '
    <p>
        Bitte wählen Sie eine/n Schüler/in von der linken Seite. Alle verbundenen
        Erziehungsberechtigten werden auf der rechten Seite als aktiv markiert.
        Sie können eine Verbindung zwischen Schüler/innen und ihren Erziehungsberechtigten
        jederzeit setzen und lösen, indem Sie auf der rechten Seite auf den Namen
        des Erziehungsberechtigten klicken.
    </p>
    <p>
        Achtung, die ausgewählten Personen erhalten auch Zugriff auf etwaige in
        der Vergangenheit ausgetauschte Informationen, die den/die Schüler/in
        betreffen.
    </p>';
$string['assistant_users_relation_nouserwarning'] = 'Sie müssen zuerst Teilnehmer/innen einschreiben, vor Sie fortsetzen können!';

$string['condition'] = 'Voraussetzung';
$string['condition_coursegroupmode'] = 'Gruppenmodus des Kurses';
$string['condition_coursegroupmode_description'] = 'Der Gruppenmodus kann für den gesamten Kurs über die Kurseinstellungen erzwungen werden. Das sollte für das Mitteilungsheft <strong>nicht</strong> gesetzt sein.';
$string['condition_forcedsubscription'] = 'Verpflichtendes Abonnement für:';
$string['condition_forcedsubscription_description'] = 'Das verpflichtende Abonnement sollte aktiviert werden, damit alle Nutzer/innen über neue Nachrichten informiert werden.';
$string['condition_generalforum'] = 'Forum für den allgemeinen Austausch';
$string['condition_generalforum_defaultname'] = 'Forum für den allgemeinen Austausch';
$string['condition_generalforum_description'] = 'Das Forum für den allgemeinen Austausch ermöglicht eine freie Kommunikation zwischen allen Teilnehmer/innen. Hier können allgemeine Informationen weitergegeben werden und man kann über allgemeine Themen miteinander diskutieren.';
$string['condition_groupforum'] = 'Forum for den privaten Austausch';
$string['condition_groupforum_defaultname'] = 'Forum für sensible Nachrichten';
$string['condition_groupforum_description'] = 'Dieses Forum basiert auf der Funktion "getrennte Gruppen" und ermöglicht so einen privaten Kommunikationskanal zu Schüler/innen und ihren jeweiligen Erziehungsberechtigten. Hier können auch sensible Informationen ausgetauscht werden.';

$string['default_group_all'] = 'Allgemeine Gruppe für alle';

$string['edureportbook:manage'] = 'Verwalten';

$string['invalid_forum'] = 'Ungültiges Forum angegeben.';

$string['missing_configuration'] = 'Fehlende Konfiguration. Bitte kontaktieren Sie Ihre Moodle-Admins';

$string['proceed_to_course'] = 'Weiter zum Kurs';
$string['proceed_to_enrolments'] = 'Einschreibung von Kursteilnehmer/innen';
$string['proceed_to_last_step'] = 'Zurück zum vorhergehenden Schritt';
$string['proceed_to_next_step'] = 'Weiter zum nächsten Schritt';

$string['resolve'] = 'lösen';

$string['role_missing_configuration'] = 'Der Mitteilungsheft-Assistent funktioniert am Besten, wenn es spezifische Rollen für Schüler/innen und Erziehungsberechtigte gibt. Bitte ersuchen Sie Ihre Moodle-Adminstration solche Rollen einzurichten!';
$string['role_legalguardian'] = 'Erziehungsberechtigte/r';
$string['role_legalguardian:description'] = 'Wählen Sie die Rolle, die Erziehungsberechtigte in Ihren Kursen erhalten.';
$string['role_student'] = 'Schüler/in';
$string['role_student:description'] = 'Wählen Sie die Rolle, die SchülerInnen in Ihren Kursen erhalten.';

$string['separate'] = 'Privatsphäre';
$string['separate:description'] = 'Um private Kommunikationskanäle rund um jedeN SchülerIn aufzubauen empfiehlt es sich den Gruppenmodus zu aktivieren. Die Empfehlung lautet "Getrennte Gruppen" einzustellen, damit die Kommunikation auch wirklich von niemand anderem gelesen werden kann. Die hier getroffene Einstellung ist für den ganzen Kurs bindend!';
$string['separate:error'] = 'Konnte die Privatsphäre einstellen!';
$string['separate:success'] = 'Privatsphäre wurde eingestelltt!';

$string['step_enrol'] = 'SchülerInnen';
$string['step_enrol_legalguardians'] = 'Erziehungsberechtigte';
$string['step_remove_block'] = 'Assistent entfernen';
$string['step_separate_groups'] = 'Privatsphäre aktivieren';
$string['step_studentassign'] = 'Beziehungen festlegen';
