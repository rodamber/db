## Modelo Relacional

Fiscal(__ID__, Empresa)

fiscaliza(__ID__, __Morada__, __Codigo__)
    ID: FK Fiscal (ID)
    Morada, Codigo: FK Alugavel(Morada, Codigo)

Alugavel(__Morada__, __Codigo__, NIF, Foto)
    Morada: FK Edificio(Morada)
    NIF: FK User(NIF)

Edificio(__Morada__)

Posto(__MoradaPosto__, __CodigoPosto__, MoradaEspaco, CodigoEspaco)
    MoradaPosto, CodigoPosto: FK Alugavel(Morada, Codigo)
    MoradaEspaco, CodigoEspaco: FK Espaco(Morada, Codigo)

Espaco(__Morada__, __Codigo__)
    Morada, Codigo: FK Alugavel(Morada, Codigo)

Oferta(__Morada__, __Codigo__, __data_inicio__, __Numero__)
    NIF: FK User(NIF)
    Morada, Codigo, data_inicio: FK Oferta(Morada, Codigo, data_inicio)
    Numero: FK Reserva(Numero)

Paga(__Numero__, data, metodo)
    Numero: FK Reserva(Numero)

Estado(__Numero__, __Timestamp__, estado)
    Numero: FK Reserva(Numero)

## Restrições de Integridade

1. Quando um elemento da relação Edificio é eliminado, os elementos
   correspondentes da relação Alugavel devem ser eliminados.
2. Quando um elemento da relação Alugavel é eliminado, os elementos
   correspondentes das relações Oferta, Posto e Espaco devem ser eliminados.
3. Quando um elemento da relação Reserva é eliminado, os elementos
   correspondentes das relações Paga e Estado devem ser eliminados.
4. Posto AND Espaco COVERS Alugavel.
5. O atributo “estado” da relação Estado pode conter os valores “Pendente”,
   “Aceite”, “Declinada” ou “Cancelada”.
6. Uma reserva só pode ser paga se o estado actual for “Aceite”.
7. No máximo, só pode existir uma reserva aceite sobre cada oferta.
8. As ofertas para o mesmo alugável não se podem sobrepor no tempo.
9. O atributo “Codigo” da entidade Alugavel deve ser um número sequencial.
