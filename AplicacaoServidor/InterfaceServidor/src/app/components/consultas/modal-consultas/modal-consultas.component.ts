import { Component, OnInit, Inject } from '@angular/core';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatDialog } from '@angular/material/dialog';
import { PacienteService } from '../../../services/paciente/paciente.service'
import { ConsultasService } from 'src/app/services/consulta/consultas.service';
import { TipoConsultaService } from 'src/app/services/tipoconsulta/tipo-consulta.service';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
  selector: 'app-modal-consultas',
  templateUrl: './modal-consultas.component.html',
  styleUrls: ['./modal-consultas.component.css']
})
export class ModalConsultasComponent implements OnInit {

  formularioConsulta: FormGroup;
  pacientes = [];
  categoriasConsulta = [];
  acaoModal;
  consulta: any;
  executandoRequisicao = false

  constructor(
    public dialogRef: MatDialogRef<ModalConsultasComponent>,
    @Inject(MAT_DIALOG_DATA) public data,
    private formBuilder: FormBuilder,
    private _snackBar: MatSnackBar,
    private dialog: MatDialog,
    private consultaService: ConsultasService,
    private tipoConsultaService: TipoConsultaService
  ) {
    this.acaoModal = data.acao;
  }

  ngOnInit() {
    this.carregarCategoriasConsulta();
    this.inicializaFormulario();

  }
  onNoClick() {
    this.dialogRef.close();
  }

  carregarCategoriasConsulta() {
    this.tipoConsultaService.listaDeTipoConsultas().subscribe(response => {
      response.forEach(tipo => {
        this.categoriasConsulta.push(tipo);
      })
    })
  }

  inicializaFormulario() {
    this.consultaService.lerConsulta(this.data.id).subscribe(response => {
      this.consulta = response;
      this.formularioConsulta = this.formBuilder.group({
        codigo: [{
          value: this.consulta.codConsulta,
          disabled: this.acaoModal == 'EDITAR' ? false : true,
        }, Validators.required],
        validade: [{
          value: this.consulta.validade,
          disabled: this.acaoModal == 'EDITAR' ? false : true,
        }, Validators.required],
        status: [{
          value: null,
          disabled: this.acaoModal == 'EDITAR' ? false : true,
        }, Validators.required],
        categoria: [{
          value: null,
          disabled: this.acaoModal == 'EDITAR' ? false : true,
        }, Validators.required],
        inicio: [{
          value: null,
          disabled: this.acaoModal == 'EDITAR' ? false : true,
        }, Validators.required],
        termino: [{
          value: null,
          disabled: this.acaoModal == 'EDITAR' ? false : true,
        }, Validators.required]
      })
    })
  }
  toggleMode(novaAcao) {
    this.acaoModal = novaAcao;
    switch (this.acaoModal) {
      case 'VISUALIZAR':
        for (let control in this.formularioConsulta.controls) {
          this.formularioConsulta.get(control).disable();
        }
        break;
      case 'EDITAR':
        for (let control in this.formularioConsulta.controls) {
          this.formularioConsulta.get(control).enable();
        }
        break;
    }
  }
  async editarConsulta() {
    let form = this.formularioConsulta.value;
    for (let campo in form) {
      if (form[campo] == null) return;
    }
    this.executandoRequisicao = true;
    await this.consultaService.editarConsulta(form).subscribe(response => {
      this.openSnackBar('Atualizacao efetuada!', 1);
      this.inicializaFormulario();
      this.toggleMode('VISUALIZAR');
    }, (err: HttpErrorResponse) => {
      this.openSnackBar('Erro, atualização não realizada!', 0);
    })
    this.executandoRequisicao = false;
  }
  openSnackBar(mensagem, nivel) {
    switch (nivel) {
      case 1:
        nivel = "alerta-sucesso";
        break;
      case 0:
        nivel = 'alerta-fracasso';
        break;
    }
  }
  deletarConsulta() {
    this.consultaService.deletarConsulta(this.data.id).subscribe(response => {
      this.openSnackBar('Consulta deletada com sucesso!', 1);
    }, (err: HttpErrorResponse) => {
      this.openSnackBar('Não foi possível deletar a consulta!', 0);
    })
  }
}