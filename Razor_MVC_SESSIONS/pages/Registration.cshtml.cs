using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using AcademicManagement;
using Microsoft.AspNetCore.Mvc.Rendering;
using Microsoft.AspNetCore.Http;

namespace Lab3.Pages
{
    public class RegistrationModel : PageModel
    {

        public List<Student> students = new();
        [BindProperty]
        public string SelectedStudentID { get; set; }
        public List<SelectListItem> Options { get; set; }
        [BindProperty]
        public List<String> SelectedCoursesID { get; set; }
        public List<Course> CourseSelections { get; set; }
        [BindProperty]
        public List<AcademicRecord> SelectedStudentRecords { get; set; }


        void SetOptions()
        {
            students = DataAccess.GetAllStudents();
            Options = new List<SelectListItem>();
            foreach (Student student in students)
            {
                Options.Add(new SelectListItem { Value = student.StudentId, Text = student.StudentName + "  " + student.StudentId });
            }
        }

        void SetCourseSelections()
        {
            CourseSelections = new();
            CourseSelections = DataAccess.GetAllCourses();
        }

        public void OnGet(string orderby)
        {
            SetOptions();
            SetCourseSelections();

            SelectedStudentID = HttpContext.Session.GetString("SelectedStudentID");

            if (SelectedStudentID != null && SelectedStudentID != "")
            {
                SelectedStudentRecords = DataAccess.GetAcademicRecordsByStudentId(SelectedStudentID);

                switch (orderby)
                {
                    case "code":
                        if (HttpContext.Session.GetString("orderby") == "code")
                        {
                            SelectedStudentRecords.Sort((r1, r2) => r2.CourseCode.CompareTo(r1.CourseCode));
                            CourseSelections.Sort((r1, r2) => r2.CourseCode.CompareTo(r1.CourseCode));
                            HttpContext.Session.SetString("orderby", "");
                        }
                        else
                        {
                            SelectedStudentRecords.Sort((r1, r2) => r1.CourseCode.CompareTo(r2.CourseCode));
                            CourseSelections.Sort((r1, r2) => r1.CourseCode.CompareTo(r2.CourseCode));
                            HttpContext.Session.SetString("orderby", orderby);
                        }
                        break;
                    case "title":
                        List<Course> _courses = new();
                        Dictionary<String, double> _tempRecord = new();
                        if (SelectedStudentRecords != null && SelectedStudentRecords.Count != 0)
                        {

                            foreach (AcademicRecord record in SelectedStudentRecords)
                            {
                                _tempRecord.Add(record.CourseCode, record.Grade);
                            }

                            foreach (AcademicRecord record in SelectedStudentRecords)
                            {
                                _courses.Add(DataAccess.GetAllCourses().Where(c => c.CourseCode == record.CourseCode).First());
                            }
                        }
                        if (HttpContext.Session.GetString("orderby") == "title")
                        {
                            CourseSelections.Sort((r1, r2) => r2.CourseTitle.CompareTo(r1.CourseTitle));
                            _courses.Sort((r1, r2) => r2.CourseTitle.CompareTo(r1.CourseTitle));
                            HttpContext.Session.SetString("orderby", "");
                        }
                        else
                        {
                            CourseSelections.Sort((r1, r2) => r1.CourseTitle.CompareTo(r2.CourseTitle));
                            _courses.Sort((r1, r2) => r1.CourseTitle.CompareTo(r2.CourseTitle));
                            HttpContext.Session.SetString("orderby", orderby);

                        }
                        for (int i = 0; i < SelectedStudentRecords.Count; i++)
                        {
                            SelectedStudentRecords[i].CourseCode = _courses[i].CourseCode;
                            SelectedStudentRecords[i].Grade = _tempRecord.Where(c => c.Key == SelectedStudentRecords[i].CourseCode).First().Value;
                        }
                        break;
                    case "grade":
                        if (HttpContext.Session.GetString("orderby") == "grade")
                        {
                            SelectedStudentRecords.Sort((r1, r2) => r2.Grade.CompareTo(r1.Grade));
                            HttpContext.Session.SetString("orderby", "");
                        }
                        else
                        {
                            SelectedStudentRecords.Sort((r1, r2) => r1.Grade.CompareTo(r2.Grade));
                            HttpContext.Session.SetString("orderby", orderby);
                        }
                        break;
                }
            }

        }

        public void OnPostStudentSelected()
        {
            SetOptions();
            SetCourseSelections();

            HttpContext.Session.SetString("SelectedStudentID", SelectedStudentID);
            SelectedStudentRecords = DataAccess.GetAcademicRecordsByStudentId(SelectedStudentID);
        }
        public void OnPostRegister()
        {
            SelectedStudentID = HttpContext.Session.GetString("SelectedStudentID");

            SetOptions();
            SetCourseSelections();

            foreach (String cID in SelectedCoursesID)
            {
                DataAccess.AddAcademicRecord(new AcademicRecord(SelectedStudentID, cID));
            }

            SelectedStudentRecords = DataAccess.GetAcademicRecordsByStudentId(SelectedStudentID);
        }

        public void OnPostGrades()
        {
            SetOptions();
            SetCourseSelections();

            foreach (AcademicRecord record in SelectedStudentRecords)
            {
                AcademicRecord _record = DataAccess.GetAcademicRecordsByStudentId(SelectedStudentID).Where(c => c.CourseCode == record.CourseCode).First();
                _record.Grade = record.Grade;
            }

            SelectedStudentRecords = DataAccess.GetAcademicRecordsByStudentId(SelectedStudentID);
        }
    }
}
