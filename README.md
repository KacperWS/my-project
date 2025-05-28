Projekt zbudowany wg intrukcji z github.com/pimcore/demo na docker. 

Struktura danych(klasa) nazywa się produkt i ma następujące pola: 
SKU(Text - Input), name(Text - Input), description(Textarea), price(Number min. 0), availiability_status(Select), category(Select), manufacturer(Text-Input), images(Image Gallery), technical_docs(Link)
Dodane zostało pole marking(Text, hidden), bo było wymagane przez framework aby dodawać produkty przez skrypt importujący - wynikało to z tego że klasę Produkt utworzyłem przez panel admina Pimcore i jest ona rozszerzeniem klasy Concrete.

Skrypt importujący oraz eksportujący dane znajduje się w "/src/Command". 
Skrypt importujący to prosty kod z pętlą while do czytania danych z .csv i zapisywaniem każdego wczytanego produktu. Na koniec na wyjście wypisywane jest komunikat o pomyślnym wykonaniu.
Skrypt eksportujący to prosty kod z pętlą foreach, na koniec zebrane dane produktów są przekształcane w plik .json. Po zakończeniu na wyjście wypisywane jest komunikat o pomyślnym wykonaniu.

Uruchomione zostały używając komend o strukturze: docker compose exec php bin/console app:{nazwa skryptu}.

Dane(plik z rozszerzeniem .csv) zostały umieszczone w katalogu "/var/import" a plik .json z eksportu w "/var/export".
