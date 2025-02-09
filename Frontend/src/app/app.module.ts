import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { provideHttpClient, withInterceptorsFromDi } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { EtablissementComponent } from './components/etablissement/etablissement.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';



@NgModule({
  declarations: [
    AppComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    EtablissementComponent,
    BrowserAnimationsModule,
  ],
  providers: [
    provideHttpClient(withInterceptorsFromDi()),

  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
