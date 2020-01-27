# PROMETHEE
Helper Algoritma PROMETHEE pada PHP

# Preference Ranking Organization for Enrichment Evaluation

Metode dalam memecahkan permasalahan yang bersifat multikriteria dengan cara menentukan urutan (prioritas). 
Masalah pokoknya adalah kesederhanaan, kejelasan , dan kestabilan. 
Dugaan dari dominasi kriteria yang digunakan dalam promethee adalah penggunaan nilai dalam hubungan outranking. 
Ini adalah metode peringkat yang cukup sederhana dalam konsep dan aplikasi dibandingkan dengan metode lain untuk analisis multikriteria. 
Promethee ini sendiri termasuk dalam keluarga dari metode outranking yang dikembangkan oleh B. Roy

## Meliputi 2 fase :
1. Membangun hubungan outranking dari K
2. Eksploitasi dari hubungan ini memberikan jawaban optimasi kriteria dalam paradigma permasalahan multikriteria.

## Langkah-langkah PROMETHEE :
1. Menentukan kriteria
2. Menentukan bobot atau nilai dari masing2 kriteria
3. Menentukan tipe preferensi untuk menentukan tipe perhitungan dalam mengolah alternatif.
4. Menentukan hasil nilai prefrensi berdasarkan tipe preferensi yang ditentukan
5. Menentukan nilai index preferensi untuk menghitung nilai preferensi dari masing2 kriteria
6. Menentukan promethee ranking yang terdiri dari nilai promethee I dan nilai promethee II. Nilai promethee I menentukan nilai Leaving Flow, Entering Flow. Pada promethee II menentukan nilai Net Flow.
