#!/bin/bash

# cria thumbnail
nome=1; 
for i in `ls cover*jpg`; do 
	convert -geometry 180x145\! `echo "cover_issue_"$nome"_pt_BR.jpg thumb_cover_issue_"$nome"_pt_BR.jpg"` ; 
echo "cover_issue_"$nome"_pt_BR.jpg thumb_cover_issue_"$nome"_pt_BR.jpg";
	nome=$((nome+1));
done;

# cria tamanho medio
nome=1;
for i in `ls cover*jpg`; do 
	convert -geometry 380x `echo "cover_issue_"$nome"_pt_BR.jpg med_cover_issue_"$nome"_pt_BR.jpg"` ; 
	nome=$((nome+1));
done;

# limita tamanho grande
nome=1;
for i in `ls conver*jpg`; do
	mogrify -geometry 400x $i;
done;

