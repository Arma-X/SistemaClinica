import '../polyfills'
import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { 
	MatSidenavModule, 
	MatListModule, 
	MatTableModule, 
	MatPaginatorModule, 
	MatSortModule, 
	MatFormFieldModule, 
	MatInputModule, 
	MatPaginatorIntl, 
	MatButtonModule,
	MatSelectModule,
	MatGridListModule,
	MatProgressBarModule,
	MatSnackBarModule,
	MatDialogModule
} from  '@angular/material';

import { getPortuguesePaginatorIntl } from './portuguese-paginator-initl';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { FormsModule } from '@angular/forms';
import { ReactiveFormsModule } from '@angular/forms';
import {MatAutocompleteModule} from '@angular/material/autocomplete';

//------------------------------- Componentes ------------------------------------

import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { InicioComponent } from './inicio/inicio.component';
import { SidenavComponent, PreAgendamento } from './sidenav/sidenav.component';
import { EmpresasComponent } from './empresas/empresas.component';
import { AgendadosComponent } from './agendados/agendados.component';
import { PreagendarComponent } from './preagendar/preagendar.component';
import { FuncoesComponent } from './funcoes/funcoes.component';
import { AtividadesComponent } from './atividades/atividades.component';
import { ExamesComponent } from './exames/exames.component';
import { PacientesComponent } from './pacientes/pacientes.component';
import { MedicosComponent } from './medicos/medicos.component';
import { SubgruposComponent } from './subgrupos/subgrupos.component';

//------------------------------- serviços ------------------------------------
import { HttpClientModule,HTTP_INTERCEPTORS} from '@angular/common/http';
import {EmpresasService} from './services/empresas/empresas.service';
import { FuncaoService } from './services/funcao/funcao.service';
import { SubgrupoService } from './services/subgrupo/subgrupo.service';
import {PacientesService} from './services/pacientes/pacientes.service';
import {ExamesService} from './services/exames/exames.service';
import {AtividadeService} from './services/atividade/atividade.service';
import {MedicoService} from './services/medico/medico.service';

//------------------------------- Pipes (pesquisas) ------------------------------------

import { CheckedPipe } from './preagendar/checked.pipe';
import { EmpresasPipe } from './empresas/empresas.pipe';
import { FuncaoPipe } from './funcoes/funcao.pipe';
import { AtividadesPipe } from './atividades/atividades.pipe';
import { MedicoPipe } from './medicos/medico.pipe';
import { Interceptor } from './services/header.interceptor'

@NgModule({
  declarations: [
    AppComponent,
    InicioComponent,
    SidenavComponent,
    EmpresasComponent,
    AgendadosComponent,
    PreagendarComponent,
    FuncoesComponent,
    AtividadesComponent,
    ExamesComponent,
    PacientesComponent,
    MedicosComponent,
    SubgruposComponent,
    CheckedPipe,
    PreAgendamento,

    EmpresasPipe,
    FuncaoPipe,
    AtividadesPipe,
    MedicoPipe,
  ],
  entryComponents:[PreagendarComponent,PreAgendamento]
  ,
  imports: [
    BrowserModule,
    AppRoutingModule,
    MatSidenavModule,
    MatListModule,
    BrowserAnimationsModule,
    MatDialogModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    MatAutocompleteModule,
    MatSidenavModule, 
    MatListModule, 
    MatTableModule, 
    MatPaginatorModule, 
    MatSortModule, 
    MatFormFieldModule, 
    MatInputModule, 
    MatButtonModule,
    MatSelectModule,
    MatGridListModule,
    MatProgressBarModule,
    MatSnackBarModule,
    MatDialogModule,
    
  ],
  providers: [
    {
			provide: HTTP_INTERCEPTORS,
			useClass: Interceptor,
			multi: true
    },
    { provide: MatPaginatorIntl, useValue: getPortuguesePaginatorIntl() },
    EmpresasService,
    FuncaoService,
    SubgrupoService,
    PacientesService,
    ExamesService,
    AtividadeService,
    MedicoService,
  ],
  bootstrap: [AppComponent,PreagendarComponent]
})
export class AppModule { }
