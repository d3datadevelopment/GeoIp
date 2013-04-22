#!/usr/bin/perl

print "*** GEO-IP Datenrotator Alexander Fehler ***\n\n\n";
@Zeilen = ("");
open(GEODATA, "<geoip.csv") || die " ******************************************** \n Datei konnte nicht gefuden werden:\n Bitte Ueberpruefen Sie den Pfad. \n\n Die .csv Datei muss im selben Ordner liegen und geoip.csv heissen\n ********************************************\n\n\n";
while(<GEODATA>)
{   
    $index=0;
    @string=split(/,/,$_); 
    for(@string)
    {
      if($index==0)
      {
        $str = "@string[$index]";  
        $index++;
      }
      else
      {
       
       $str = "$str,@string[$index]";
       $index++; 
      }
      
        
    }
    chomp($str);  
    push(@Zeilen_neu,$str);
}

close(GEODATA); 
print "*** Datei wurde eingelesen und geschlossen *** \n ";
print "\n\n";

print "*** Datei wird zum Schreiben geoeffnet ***\n";
open(GEODATASQL, ">geoip.sql");   # SQL-Datei zum Schreiben öffnen
$schreib_zaehler=0;
for(@Zeilen_neu)                        # solange Daten in der GEOIP-Liste sind
{
 #   print @Zeilen_neu[$schreib_zaehler];
    #print "\n\n\n\ntest   ".$_."\n\n\n\n";
    print GEODATASQL "INSERT INTO  d3geoip VALUES(@Zeilen_neu[$schreib_zaehler]);\n";   # Aktuellen Datensatz schreiben
    $schreib_zaehler++;
}
# print GEODATASQL "INSERT INTO  d3geoip VALUES(@Zeilen_neu[$schreib_zaehler]);\n";   # Aktuellen Datensatz schreiben
close(GEODATASQL); 
print "\n Es wurden d",$schreib_zaehler," Datensaetze geschrieben\n";   # Nur zur Kontrolle: auf Standardausgabe*/



#@string=split(/,/,$string);
#$index=0;
#for(@string)
#{
    #$str = "$str,@string[$index]";
    
#}    

#print $str."\n";
#push(@zeile,$str);
#print @zeile."\n";
                    