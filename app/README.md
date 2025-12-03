# U-Request

rebuild:
npx tailwindcss -i ./input.css -o ./public/assets/css/output.css --watch


File Stucture:
app/
├── config/                # Config files (constants, db config, etc.)
├── core/                  # Base classes (BaseModel, BaseController, etc.)
├── controllers/           # Global controllers (if needed)
├── models/                # Global models (if needed)
├── modules/
│    ├── gsu_admin/
│    │    ├── controllers/
│    │    ├── models/
│    │    └── views/
│    ├── motorpool_admin/
│    │    ├── controllers/
│    │    ├── models/
│    │    └── views/
│    └── user/
│         ├── controllers/
│         ├── models/
│         └── views/
├── shared/
│    └── views/            # Layouts, headers, footers, reusable parts
└── public/
     ├── css/   
     ├── img/   
     ├──  js/  
     └── upload/       
          └── profile_pics/               
          └── vehicles/   
          └── repair/    
