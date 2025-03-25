import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { StudentListComponent } from './student-list/student-list.component';
import { AddStudentComponent } from './add-student/add-student.component';
import { AuthService } from './auth.service';
import { StudentService } from './student.service';
import { AuthGuard } from './auth.guard';
import { EditStudentComponent } from './edit-student/edit-student.component';
import { TestListComponent } from './test-list/test-list.component';
import { AddTestComponent } from './add-test/add-test.component';
import { EditTestComponent } from './edit-test/edit-test.component';
import { AutoEcoleListComponent } from './auto-ecole-list/auto-ecole-list.component';
import { AddAutoEcoleComponent } from './add-auto-ecole/add-auto-ecole.component';
import { EditAutoEcoleComponent } from './edit-auto-ecole/edit-auto-ecole.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    StudentListComponent,
    AddStudentComponent,
    EditStudentComponent,
    TestListComponent,
    AddTestComponent,
    EditTestComponent,
    AutoEcoleListComponent,
    AddAutoEcoleComponent,
    EditAutoEcoleComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    FormsModule,
    AppRoutingModule
  ],
  providers: [AuthService, StudentService, AuthGuard],
  bootstrap: [AppComponent]
})
export class AppModule { }