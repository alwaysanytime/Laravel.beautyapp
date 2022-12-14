<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>A simple, clean, and responsive HTML invoice template</title>
</head>

<link media="all" type="text/css" rel="stylesheet" href="{{asset("public/assets/css/pdfprint.css")}}">

<body>
{{--<div class="invoice-box">--}}
<table cellpadding="0" cellspacing="0">
    <tr class="top">
        <td style="text-align: center; font-size: medium; padding-bottom: 20px" colspan="2">
						<img src="{{public_path("/media/medical_hairless_esthetic_300.png")}}" style="width: 300px; height: 129px"/>
						<br>
						<br>
						<h3 style="font-weight: bold;text-align: center;">BEHANDLUNGSVERTRAG<br><br><font size="4">Laserhaarentfernung</font></h3>
        </td>
    </tr>

    <tr class="information">
        <td style="text-align: center; font-size: medium; padding-bottom: 20px" colspan="2">
    			  <b>zwischen</b>
    				<br>
    				<br>
            Medical Hairless & Esthetic<br/>
            Bahnhofstr. 1<br/>
            59065 Hamm<br/>
            Deutschland<br/>
            <br>
            (Behandlungsinstitut)
            <br>
            <br>
            <b>und</b>
            <br>
            <br>
            <?=$data[1]->firstname . " " . $data[1]->lastname?><br/>
            <?=$data[1]->address1?><br/>
            <?=$data[1]->zipcode . " " . $data[1]->city?><br/><br/>
            <br>
            (Kunde)
            <br>
        </td>
    </tr>
    <tr>
        <td style="text-align: center; font-size: medium; padding-bottom: 20px" colspan="2">
            wird ein Behandlungsvertrag zur dauerhaften Haarentfernung in den im
            Folgenden genannten Arealen zu nachstehenden Konditionen vereinbart: <br>
        </td>
    </tr>
    <tr style="line-height: 3px;" class="heading">
        <td></td>
        <td></td>
    </tr>
    <tr class="details">
        <td style="padding-top: 15px;">pro Behandlung</td>
        <td style="padding-top: 15px; <?=$data[5] == "LUMP" ? "color: gray;" : null?>"><b>Ihr Medical Hairless & Esthetic Paket-Preis: <?= $data[6].".00???"?></b></td>
    </tr>

    <tr class="heading">
        <td>Therapies</td>
        <td>Behandlung</td>
    </tr>
    <?php var_dump($data[2]); ?>
    
    <?php foreach ($data[2] as $services):?>
    <tr class="item<?=$data[2][count($data[2]) - 1] == $services ? " last" : null?>">
        <td style="padding-top: 15px;"><?=$services?></td>
        <td style="font-size: small; padding-top: 15px"><?= $data[5] == "LUMP" ? 'inklusive' : "8 Behandlungen"?></td>
    </tr>
    <?php endforeach;?>
    <tr class="total">
        <td></td>
        <td style="padding: 15px"><b>Ihr Medical Hairless & Esthetic Gesamt-Preis: <?= $data[5] == "LUMP" ? $data[6] : $data[6] * 8?>.00???</b></td>
    </tr>
    <tr style="line-height: 3px" class="heading">
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td style="text-align: center; font-size: medium; padding-bottom: 25px; padding-top: 35px" colspan="2">
            Das Behandlungsinstitut weist den Kunden darauf hin, dass die
            <strong>auf der R??ckseite dieses Behandlungsvertrages</strong>
            aufgedruckten <strong>Allgemeinen Gesch??ftsbedingungen des Behandlungsinstituts</strong>
            fester Bestandteil und Grundlage des Behandlungssvertrages sind.
        </td>
    </tr>
    <tr class="heading">
        <td>Zahlungswunsch</td>
        <td>Notiz</td>
    </tr>
    <tr>
        <td style="padding-top: 15px;"><?=$data[5] == "LUMP" ? "Pauschal" : $data[5]?> - <?= $data[5] == "LUMP" ? $data[6] : $data[6] * 8?>.00???</td>
        <td style="padding-top: 15px; font-size: small; text-align: left"><?=empty($data[9]) ? "Keine Notizen deklariert..." : $data[9]?><br>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 35px">Hamm
            <div style="border-bottom: 2px solid #bbb;"></div>
            <small>Ort</small></td>
        <td style="text-align: left; padding-top: 35px"><?=$data[3]?>
            <div style="border-bottom: 2px solid #bbb;"></div>
            <small>Datum</small></td>
    </tr>
    <tr style="padding-top: 20px">
        <td><img src="{{base_path("/downloads/tmp/client.png")}}"
                 style="width: 400px; height: 70px; border-bottom: 2px solid #bbb;"/><br><small>Unterschrift Kunde /
                Erziehungsberechtiger</small></td>
        <td style="text-align: left"><img src="{{base_path("/downloads/tmp/worker.png")}}"
                                          style="width: 400px; height: 70px; border-bottom: 2px solid #bbb;"/><br><small>Unterschrift
                Therapeut</small></td>
    </tr>
</table>
<p style="page-break-after: always"></p>
<table cellpadding="0" cellspacing="0">
    <tr class="heading">
        <td>Allgemeine Gesch??ftsbedingungen</td>
        <td></td>
    </tr>
    <tr>
        <td style="font-size: xx-small; padding-top: 15px; width=45%">
        		<strong style="font-size: x-small;">?? 1 Geltungsbereich</strong><br><br>						
						Die vorliegenden Allgemeinen Gesch??ftsbedingungen (AGB) gelten zwischen der 
						Medical Hairless & Esthetic Praxis in Hamm und unseren Kunden. Es gelten 
						ausschlie??liche diese AGB. Abweichende Regelungen bed??rfen der schriftlichen 
						Zustimmung.<BR><br>
        		<strong style="font-size: x-small;">?? 2 Vertragsinhalt</strong><br><br>											
						Der Vertragsinhalt ergibt sich aus der zwischen uns, der Medical Hairless & Esthetic 
						Praxis in Hamm, und unseren Kunden geschlossenen Behandlungsvereinbarung.<BR><br> 
        		<strong style="font-size: x-small;">?? 3 Leistungserfolg</strong><br><br>												
						(1) Bei der zwischen Ihnen und uns geschlossenen Behandlungsvereinbarung handelt 
						es sich um einen Dienstvertrag. Ein Erfolg ist von uns nicht geschuldet.<BR>
						(2) Wir ??bernehmen auch keine Garantie f??r einen bestimmten Leistungserfolg. <BR><br>
        		<strong style="font-size: x-small;">?? 4 Pflichten unserer Kunden</strong><br><br>												
						(1) Durch den Abschluss der Behandlungsvereinbarung verpflichtet sich der Kunde, 
						den bzw. die mit uns vereinbarten Behandlungstermine, falls Folgetermine vereinbart 
						wurden, einzuhalten.<BR>
						(2) Zur Sicherung der mit uns vereinbarten Termine ist eine Vorauszahlung in H??he 
						von 50,00 ??? zu leisten. Die Vorauszahlung ist jeweils zum Zeitpunkt der jeweiligen 
						Vereinbarung eines Behandlungstermins zahlbar und f??llig. Vor der Leistung der 
						Vorauszahlung wird kein neuer Behandlungstermin vereinbart.<BR> 
						(3) Bei vertragsgem????er Wahrnehmung der vereinbarten Termine werden die 
						geleisteten Vorauszahlungen auf Wunsch des Kunden entweder ausgezahlt oder auf 
						die Gesamtverg??tung angerechnet.<BR>
						(4) Termine, die aus vom Kunden zu vertretenen Gr??nden nicht eingehalten werden 
						k??nnen, sind sp??testens 48 Stunden vor dem vereinbarten Termin abzusagen.<BR>
						(5) Unterbleibt die rechtzeitige Absage oder erscheint der Kunde zu einem 
						vereinbarten Termin nicht, so sind wir berechtigt, die Vorauszahlung in H??he von 50,00 
						??? als Ausfallpauschale einzubehalten. Eine Verrechnung auf Folgetermine sowie eine 
						Auszahlung entfallen in diesem Fall.<BR>
						(6) Der Kunde ist, um einen ordnungsgem????en Behandlungsverlauf zu sichern, 
						verpflichtet, die auf dem Formular ???Anamnese??? gestellten Fragen vollst??ndig und 
						wahrheitsgem???? zu beantworten. K??nnen Sie bestimmte Fragen nicht beantworten, 
						sind Sie verpflichtet, uns darauf hinzuweisen. Die zur Behandlung erforderlichen 
						Informationen sind bei Nichtvorliegen nachzureichen. Wir behalten uns ausdr??cklich 
						vor, den Behandlungsbeginn von der Beantwortung der f??r die Behandlung 
						erforderlichen Fragen abh??ngig zu machen.<BR> 
						(7) ??nderungen des gesundheitlichen Zustandes sind uns unverz??glich und vor 
						Behandlungsbeginn mitzuteilen.<BR>
						(8) Unseren Anweisungen, Vorgaben und Empfehlungen vor, w??hrend sowie nach der 
						Behandlung ist Folge zu leisten.<BR> 
						(9) Falls dies im Rahmen der Behandlung erforderlich ist und Sie von uns darauf 
						hingewiesen werden, sind die zu behandelnden K??rperareale einen Tag vor der 
						Behandlung gem???? unserer Anweisungen von Ihnen vorzubereiten.<BR><br>
            <strong style="font-size: x-small">?? 5 Zahlungsmodalit??ten</strong><br><br>						
						(1) Die Zahlungsmodalit??ten ergeben sich aus dem zwischen Ihnen uns 
						geschlossenen Behandlungsvertrag. Wenn nichts Gegenteiliges vereinbart wurde, 
						zahlen Sie die jeweils anfallenden Kosten f??r den jeweiligen Behandlungstermin.<BR>
						(2) Die vereinbarte Verg??tung f??r die Gesamtbehandlung ist bei Abschluss des 
						Behandlungsvertrages insgesamt zur Zahlung f??llig, wenn dies ausdr??cklich vereinbart 
						wurde.<BR>
        </td>
        <td style="font-size: xx-small; text-align: left; padding-top: 15px; width=45%">
            <strong style="font-size: x-small">?? 6 Unser K??ndigungsrecht und Schadenersatz</strong><br><br>						
						(1) Wird die Vereinbarung und Durchf??hrung von Behandlungsterminen von Ihnen 
						verweigert und/oder werden Termine durch Sie in mehr als drei F??llen erst innerhalb 
						eines Zeitraums von weniger als 48 Stunden abgesagt, sind wir berechtigt, den 
						Behandlungsvertrag mit Ihnen zu k??ndigen.<BR> 
						(2) Im Falle einer K??ndigung nach ?? 6 Abs.1 dieser Bedingungen sind Sie verpflichtet, 
						uns einen pauschalisierte Schadenersatzleistung in H??he von 40 % des 
						verbleibenden, durch die bisherigen Behandlungen nicht in Anspruch genommenen 
						Behandlungspreises zu zahlen. Ihnen ist der Nachweis gestattet, dass uns kein 
						Schaden oder ein wesentlich niedrigerer Schaden entstanden ist. Uns wird der 
						Nachweis eines h??heren Schadens vorbehalten.<BR>
						(3) Der Schadenersatz nach ?? 6 Abs. 2 dieser Bedingungen sowie ein etwaiger, offener 
						Restbetrag f??r in Anspruch genommene Behandlungen sind mit der Erkl??rung des 
						R??cktritts durch uns sofort zahlbar und f??llig. Wir behalten uns vor, unsere Anspr??che 
						mit Ihren R??ckzahlungsanspr??chen zu verrechnen.<BR><br>						
            <strong style="font-size: x-small">?? 7 Haftung</strong><br><br>						
						(1) Unsere Haftung sowie die unserer Mitarbeiter und Erf??llungsgehilfen f??r 
						vertragliche Pflichtverletzungen sowie aus Delikt ist auf Vorsatz und grobe 
						Fahrl??ssigkeit beschr??nkt. <BR>
						(2) Dies gilt nicht bei Verletzung einer wesentlichen Vertragspflicht, d.h. einer Pflicht 
						auf deren Einhaltung Sie als Kunde vertrauen und vertrauen d??rfen. Bei leichter 
						Fahrl??ssigkeit ist die Haftung jedoch auf den Ersatz des vorhersehbaren, 
						typischerweise eintretenden Schaden begrenzt.<BR>
						(3) Die H??he eines etwaigen Schadenersatzanspruchs kann durch ein Mitverschulden 
						Ihrerseits vermindert oder vollst??ndig auf Null reduziert werden. Ein Mitverschulden 
						kann insbesondere vorliegen,<BR>
						- wenn Sie gegen Anweisungen unserer Mitarbeiter versto??en;<BR>
						- wenn Sie zum Zeitpunkt des Abschlusses der Behandlungsvereinbarung wissentlich 
						falsche Angaben gemacht haben;<BR>
						- wenn Sie die Anweisungen hinsichtlich der Vorbereitung zur jeweiligen Behandlung 
						nicht beachtet haben;<BR>
						- wenn Sie die Anweisungen zur Vor- und Nachbehandlung nicht beachtet haben.<BR><br>
            <strong style="font-size: x-small">?? 8 Sonstiges</strong><br><br>												
						Dieser Vertrag unterliegt dem Recht der Bundesrepublik Deutschland.
</td>
    </tr>
</table>
<p style="page-break-after: always"></p>
<table cellpadding="0" cellspacing="0">
    <tr style="text-align: center; padding-bottom: 15px;" class="heading">
        <td colspan="2">Einwilligungserkl??rungen</td>
        <td></td>
    </tr>
    <tr class="details">
        <td style="font-size: small; padding-top: 15px" colspan="2">
            &nbsp;&nbsp;&nbsp;<b>*</b> Ich erteile meine Einwilligung, dass das Behandlungsinstitut und die Mavi Estetik
            ve
            Saglik Merkezi Anonim Sirketi
            meine personenbezogenen Daten, die im Zusammenhang mit dem Behandlungsvertrag erhoben werden, erfassen,
            speichern, verarbeiten und f??r die Zwecke des Behandlungsvertrages, zur Kundebetreuung und f??r statistische
            Auswertungen nutzen d??rfen. <br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Ich erteile meine Einwilligung, dass meine personenbezogenen Daten sowie
            Bildaufnahmen meines Gesichtes zum
            Zweck der Hautanalyse an Mavi Estetik ve Saglik Merkezi Anonim Sirketi weitergeleitet und von diesem
            erfasst,
            gespeichert, verarbeitet und zu diesem Zweck verwendet werden.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Ich willige ein, dass mich die Mavi Estetik ve Saglik Merkezi Anonim Sirketi an
            meine
            bevorstehenden
            Behandlungstermine erinnert und mich per SMS ??ber zuk??nftige Angebote und Aktionen von Mavi Estetik ve
            Saglik
            Merkezi Anonim Sirketi informiert.<br></td>
        <td></td>
    </tr>
    <tr>
        <td style="font-size: small" colspan="2">
            Ich wei??, dass ich zur Erteilung der Einwilligungen nicht verpflichtet bin und dass mir aus einer
            Verweigerung
            der
            Einwilligungen keine rechtlichen Nachteile entstehen d??rfen. Die Einwilligungen sind jederzeit gegen??ber dem
            Behandlungsinstitut unter den dort genannten Kontaktadressen widerrufbar.<br><br><br>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 15px">Hamm
            <div style="border-bottom: 2px solid #bbb;"></div>
            <small>Ort</small></td>
        <td style="text-align: left; padding-top: 15px"><?=$data[3]?>
            <div style="border-bottom: 2px solid #bbb;"></div>
            <small>Datum</small></td>
    </tr>
    <tr style="padding-top: 15px" class="details">
        <td><img src="{{base_path("/downloads/tmp/consent_statement.png")}}"
                 style="width: 400px; height: 70px; border-bottom: 2px solid #bbb;"/><br><small>Unterschrift Kunde /
                Erziehungsberechtiger</small><br><br></td>
        <td></td>
    </tr>
</table>
<p style="page-break-after: always"></p>
<table cellpadding="0" cellspacing="0">
    <tr style="text-align: center; padding-bottom: 15px;" class="heading">
        <td colspan="2">Behandlungsinformationen</td>
        <td></td>
    </tr>
    <tr class="details">
        <td style="font-size: small; padding-top: 15px" colspan="2">
            <b>Was ist vor der Behandlung zu beachten?</b><br>
            Die zu behandelnden Stellen sollten mindestens 4 Wochen vor und nach der Behandlung keiner direkten
            Sonnenbestrahlung ausgesetzt sein und d??rfen weder mit UV-Bestrahlung im Solarium noch mit Selbstbr??uner
            behandelt werden.
            Die Stellen sollten m??glichst ungebr??unt sein. Durch die Br??une k??nnen Hautunvertr??glichkeiten entstehen,
            f??r
            die wir keine
            Haftung ??bernehmen.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Kosmetika (z.B. Cremes, Make-Up, Deodorant, etc.) m??ssen vor der Behandlung
            sorgf??ltig entfernt werden.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Sollte im Behandlungsareal schon einmal Herpes aufgetreten sein, informieren Sie
            Ihren Therapeuten bitte unbedingt<br>
            vor der Behandlung dar??ber.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Falls Sie folgende Medikamente in den letzten Wochen eingenommen haben, ist der
            Therapeut unbedingt zu
            informieren: Antibiotikum, Kortison, Johanniskraut.<br><br>
            <b>Was ist nach der Behandlung zu beachten?</b><br>
            Unmittelbar nach der Behandlung ist die Haut je nach St??rke der Bestrahlung einige Stunden lang ger??tet und
            manchmal
            leicht geschwollen.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Sollten Krusten entstehen, d??rfen diese nicht durch Kratzen oder Reiben entfernt
            werden.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Waschen Sie sich in den ersten zwei bis drei Tagen nach der Behandlung nur mit
            kaltem
            oder lauwarmem Wasser.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Tupfen Sie die Stellen vorsichtig trocken, ohne zu rubbeln.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Saunabesuche sind erst eine Woche nach der Behandlung wieder m??glich.<br><br>
            <b>Was ist w??hrend der Behandlung zu beachten?</b><br>
            Sollten sich Ver??nderungen Ihres Gesundheitszustandes oder Ihrer Medikamenteneinnahme w??hrend der Behandlung
            einstellen, m??ssen Sie uns dar??ber umgehend informieren.<br><br>
            <b>Welche Nebenwirkungen und Komplikationen k??nnen auftreten?</b><br>
            In der Regel verl??uft die Laserbehandlung ohne wesentliche Komplikationen. Trotz gr????ter Sorgfalt kann es
            jedoch
            w??hrend und nach dem Eingriff zu folgenden Nebenwirkungen kommen.<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Krustenbildung<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Oberfl??chliche Infektionen des behandelten Hautareals / Wundheilst??rungen<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Provokation eines Herpes (Fieberbl??schen) oder (extrem selten) einer G??rtelrose
            in
            dem behandelten Areal<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Allergie<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Bleibende Farbver??nderungen der Haut (Hypo- bzw. Hyperpigmentierung)<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Narbenbildung, narbige Hauteinziehungen und Narbenwucherungen (anlagenbedingte
            wulstige Narben)<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Verbrennung der Haut<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Synchronisation des Haarwachstums kann vor??bergehend zu einer st??rkeren Behaarung
            f??hren<br><br><br></td>
        <td></td>
    </tr>
    <tr style="text-align: center; padding-bottom: 15px;" class="heading">
        <td>Anamnese</td>
        <td></td>
    </tr>
    <tr>
        <td style="font-size: small; padding-top: 15px" colspan="2">
            <b>Haben Sie sich zuvor einer Laser-, Nadel-, oder IPL- Behandlung unterzogen?</b><br><?= !empty($data[7]) && $data[7]["laser"] == 1 ? "Ja" .  (array_key_exists("laser_yes",$data[7])==true?" - ".$data[7]["laser_yes"]:null) : "Nein"?><br><br>
            <b>Nehmen Sie derzeit Medikamente ein?</b><br><?= !empty($data[7]) &&$data[7]["medic"] == 1 ? "Ja" . (array_key_exists("medics",$data[7])==true?" - ".$data[7]["medics"]:null) : "Nein"?><br><br>
            <b>Besteht zur Zeit eine Schwangerschaft?</b><br><?= !empty($data[7]) && $data[7]["pregnant"] == 1 ? "Ja" : "Nein"?><br><br>
            <b>Waren Sie in den letzten 4 Wochen einer intensiven Sonnenstrahlung ausgesetzt?</b><br><?= !empty($data[7]) && $data[7]["intensivesun"] == 1 ? "Ja" : "Nein"?><br><br>
            <b>Leiden Sie unter einer der folgenden Krankheiten oder waren Sie in der Vergangenheit davon betroffen?</b><br>
            <?php
            if(empty($data[7]) || array_key_exists("Keine Krankheit", $data[7]["diseases"])){
                echo "Keine Krankheit";
            }else{
                foreach ($data[7]["diseases"] as $keys => $disease){
                    echo $keys . " - ";
                }
            }
            ?><br><br>
            <b>Besteht eine Allergie?</b><br><?= !empty($data[7]) && $data[7]["allergic"] == 1 ? "Ja" . (array_key_exists("allergic_what",$data[7])==true?" - ".$data[7]["allergic_what"]:null) : "Nein"?><br><br>
        </td>
        <td></td>
    </tr>
</table>
<p style="page-break-after: always"></p>
<table cellpadding="0" cellspacing="0">
    <tr style="text-align: center; padding-bottom: 15px;" class="heading">
        <td>Einverst??ndniserkl??rung</td>
        <td></td>
    </tr>
    <tr class="details">
        <td style="font-size: small; padding-top: 15px" colspan="2">
            <b>Hiermit best??tige ich, dass ich</b><br>
            &nbsp;&nbsp;&nbsp;<b>*</b> den vorstehenden Anamnesebogen nach bestem Wissen und Gewissen ausgef??llt
            habe;<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> wei??, dass durch falsche Angaben das Risiko von unerw??nschten Nebenwirkungen und
            Komplikationen stark erh??ht
            wird;<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> die vorstehenden Behandlungsinformationen zur dauerhaften Haarentfernung erhalten,
            sorgf??ltig gelesen und
            verstanden habe<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> Kenntnis davon habe, dass die in den Behandlungsinformationen unter
            ???Nebenwirkungen
            und Komplikationen???
            genannten Folgen auftreten k??nnen;<br>
            &nbsp;&nbsp;&nbsp;<b>*</b> von folgendem Mitarbeiter in einem Aufkl??rungsgespr??ch ausf??hrlich ??ber die
            geplante Behandlung im Bereich der dauerhaften Haarentfernung und die damit verbundenen Risiken
            informiert worden bin: <strong><?=$data[8]?></strong>
            <br><br>
            Ich habe keine weiteren Fragen, f??hle mich ausreichend aufgekl??rt und willige hiermit in die geplante
            Behandlung
            ein.<br><br>
        </td>
        <td></td>
    </tr>
    <tr>
        <td style="padding-top: 15px">Hamm
            <div style="border-bottom: 2px solid #bbb;"></div>
            <small>Ort</small></td>
        <td style="text-align: left; padding-top: 15px"><?=$data[3]?>
            <div style="border-bottom: 2px solid #bbb;"></div>
            <small>Datum</small></td>
    </tr>
    <tr style="padding-top: 15px">
        <td><img src="{{base_path("/downloads/tmp/consent_form.png")}}"
                 style="width: 400px; height: 70px; border-bottom: 2px solid #bbb;"/><br><small>Unterschrift Kunde /
                Erziehungsberechtiger</small></td>
        <td></td>
    </tr>
</table>
{{--</div>--}}
</body>
</html>
