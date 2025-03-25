import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { StudentService } from '../student.service';

@Component({
  selector: 'app-edit-student',
  templateUrl: './edit-student.component.html',
  styleUrls: ['./edit-student.component.css'],
  standalone : false
})
export class EditStudentComponent implements OnInit {
  student: any = {
    nom: '',
    prenom: '',
    date_naissance: '',
    ville: '',
    NPEH: ''
  };

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private studentService: StudentService
  ) {}

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.studentService.getStudentById(+id).subscribe(
        (data) => {
          this.student = data;
        },
        (error) => {
          console.error('Erreur lors du chargement de l\'élève', error);
        }
      );
    }
  }

  updateStudent() {
    this.studentService.updateStudent(this.student).subscribe(
      () => {
        this.router.navigate(['/students']);
      },
      (error) => {
        console.error('Erreur lors de la mise à jour', error);
      }
    );
  }
}