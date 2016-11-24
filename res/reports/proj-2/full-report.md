\newcommand\tab[1][1cm]{\hspace*{#1}}

<!-- --- -->
<!-- title: Projecto de Bases de Dados, Parte 2 -->
<!-- author: -->
<!-- - Rodolfo Cardoso (73861) -->
<!-- - Pedro Torres (78742) -->
<!-- - Rodrigo Bernardo (78942) -->
<!-- institute: Instituto Superior Técnico -->
<!-- --- -->


\begin{titlepage}
\begin{center}
 {\Huge\bfseries Projecto de Bases de Dados, Parte 2\\}

 \vspace{2cm}

 {\Large\bfseries Rodolfo Cardoso (73861)}\\[5pt]
 {\Large\bfseries Pedro Torres (78742)}\\[5pt]
 {\Large\bfseries Rodrigo Bernardo (78942)}\\[5pt]

 \vspace{2cm}

 \begin{list}{}{
        \setlength{\leftmargin}{1.5in}
        \setlength{\labelwidth}{0pt}
        \setlength{\labelsep}{1in}
            \setlength{\itemsep}{15pt}}
            \raggedright
    \item[\hbox to 5pt{\Large\bfseries Grupo:}]     {\Large 67}
    \item[\hbox to 5pt{\Large\bfseries Turno:}]     {\Large quinta-feira, 12h30}
    \item[\hbox to 5pt{\Large\bfseries Professor:}] {\Large Gabriel Pestana}
    \item[\hbox to 5pt{\Large\bfseries Data:}]      {\Large 03/11/2016}
    \item[\hbox to 5pt{\Large\bfseries Esforço:}]   {\Large 3h para cada aluno}
 \end{list}
\end{center}
\end{titlepage}

\pagebreak

# Modelo Relacional


Fiscal(\underline{ID}, Empresa)  

fiscaliza(\underline{ID}, \underline{Morada}, \underline{Codigo}, \underline{NIF})  
\tab ID: FK Fiscal(ID)  
\tab Morada, Codigo, NIF: FK arrenda(Morada, Codigo, NIF)  

User(\underline{NIF}, telefone, nome)  

arrenda(\underline{Morada}, \underline{Codigo}, NIF)  
\tab Morada, Codigo: FK Alugavel(Morada, Codigo)  
\tab NIF: FK User(NIF)  

Alugavel(\underline{Morada}, \underline{Codigo}, Foto)  
\tab Morada: FK Edificio(Morada)  

Edificio(\underline{Morada})  

Posto(\underline{Morada}, \underline{Codigo}, \underline{MoradaEspaco}, \underline{CodigoEspaco})  
\tab Morada, Codigo: FK Alugavel(Morada, Codigo)  
\tab MoradaEspaco, CodigoEspaco: FK Espaco(Morada, Codigo)  

Espaco(\underline{Morada}, \underline{Codigo})  
\tab Morada, Codigo: FK Alugavel(Morada, Codigo)  

Oferta(\underline{Morada}, \underline{Codigo}, \underline{data\_inicio}, data_fim, Tarifa)  
\tab Morada, Codigo: FK Alugavel(Morada, Codigo)  

Aluga(\underline{NIF}, \underline{Morada}, \underline{Codigo}, \underline{data\_inicio}, \underline{Numero})  
\tab NIF: FK User(NIF)  
\tab Morada, Codigo, data\_inicio: FK Oferta(Morada, Codigo, data\_inicio)  
\tab Numero: FK Reserva(Numero)  

Reserva(\underline{Numero})  

Paga(\underline{Numero}, data, metodo)  
\tab Numero: FK Reserva(Numero)  

Estado(\underline{Numero}, \underline{Timestamp}, estado)  
\tab Numero: FK Reserva(Numero)  

<!-- This is a comment -->

\pagebreak

# Restrições de Integridade
<!-- \subsection*{\centering{Restrições de Integridade}} -->

1. Quando um elemento da relação *Edificio* é eliminado, os elementos
   correspondentes da relação *Alugavel* devem ser eliminados.
2. Quando um elemento da relação *Alugavel* é eliminado, os elementos
   correspondentes das relações *Oferta*, *Posto* e *Espaco* devem ser eliminados.
3. Quando um elemento da relação *Reserva* é eliminado, os elementos
   correspondentes das relações *Paga* e *Estado* devem ser eliminados.
4. *Posto* AND *Espaco* COVERS *Alugavel*.
5. O atributo “estado” da relação *Estado* pode conter os valores “Pendente”,
   “Aceite”, “Declinada” ou “Cancelada”.
6. Uma reserva só pode ser paga se o estado actual for “Aceite”.
7. No máximo, só pode existir uma reserva aceite sobre cada oferta.
8. As ofertas para o mesmo alugável não se podem sobrepor no tempo.
9. O atributo “Codigo” da entidade *Alugavel* deve ser um número sequencial.


\pagebreak

# Álgebra Relacional
<!-- \subsection*{\centering{Álgebra Relacional }} -->

<!-- 1. *Liste a morada de todos os edifícios que contêm pelo menos um alugável com -->
<!--    mais de uma oferta.* -->

## Questão 1
\begin{align*}
   \pi_{Morada}(\sigma_{offers\, >\, 1}({}_{Morada, Codigo}G_{count()\; as\; offers}(Oferta)))
\end{align*}

<!-- 2. *Liste o estado actual de todas as reservas pagas.* -->

## Questão 2
\begin{align*}
  \pi_{Numero, estado}(Paga \bowtie Estado)
\end{align*}

<!-- 3. *Liste o identificador completo dos espaços de trabalho cujos postos nele -->
<!--    contidos foram todos alugados. Por alugado entende-se um posto de trabalho -->
<!--    que tenha pelo menos uma oferta aceite, independentemente das suas datas.* -->

## Questão 3
\begin{align*}
  \pi_{MoradaEspaco,CodigoEspaco}(\sigma_{estado="Aceite"}(Posto \bowtie Aluga \bowtie Estado))
\end{align*}


# SQL

### Questão 1
<!-- ~~~ { .SQL } -->
~~~
SELECT Morada
FROM Oferta
GROUP BY Morada, Codigo
HAVING COUNT(*) > 1
~~~
<!-- ~~~ -->

### Questão 2
<!-- ~~~ { .SQL } -->
~~~
SELECT Numero, estado
FROM Paga NATURAL JOIN Estado
~~~
<!-- ~~~ -->

