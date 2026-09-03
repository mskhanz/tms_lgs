<?php

return [
    'quiz' => [
        'title' => 'KP Local Government Act 2013 & Amendments 2022',
        'description' => 'Multiple choice assessment covering the Khyber Pakhtunkhwa Local Government Act, 2013, the 2022 Amendment Act, City/Tehsil Local Government Rules of Business 2022, local government funds, property, committees, and related provisions for trainee preparation.',
        'duration_minutes' => 90,
        'passing_percentage' => 50,
        'max_attempts' => 3,
        'shuffle_questions' => true,
        'shuffle_options' => true,
        'is_active' => true,
    ],
    'questions' => [
        // Part-I: KP Local Government Act, 2013
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'The Khyber Pakhtunkhwa Local Government Act was enacted in which year?',
            'options' => [
                'A' => '2010',
                'B' => '2011',
                'C' => '2013',
                'D' => '2015',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'The Khyber Pakhtunkhwa Local Government Act, 2013 is Act No.:',
            'options' => [
                'A' => 'XVIII of 2013',
                'B' => 'XXVIII of 2013',
                'C' => 'XXXVIII of 2013',
                'D' => 'XXII of 2013',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'The Act extends to:',
            'options' => [
                'A' => 'Peshawar only',
                'B' => 'Urban areas only',
                'C' => 'The whole Province of Khyber Pakhtunkhwa, subject to specified exclusions',
                'D' => 'District headquarters only',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'Which areas are generally excluded from the application of the Act?',
            'options' => [
                'A' => 'Rural areas',
                'B' => 'Cantonments or areas excluded by Government through notification',
                'C' => 'Tehsil headquarters',
                'D' => 'Village Councils',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'The KP Local Government Act, 2013 primarily provides for:',
            'options' => [
                'A' => 'Federal taxation',
                'B' => 'Regulation of provincial departments',
                'C' => 'Establishment and regulation of local government institutions',
                'D' => 'Establishment of courts',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'Article 140A of the Constitution relates primarily to:',
            'options' => [
                'A' => 'Provincial taxation',
                'B' => 'Local government system',
                'C' => 'Federal elections',
                'D' => 'Public service commissions',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'The Act promotes decentralization of:',
            'options' => [
                'A' => 'Political, administrative and financial responsibility and authority',
                'B' => 'Judicial authority only',
                'C' => 'Foreign affairs',
                'D' => 'Defence administration',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'Which of the following is specifically mentioned in the preamble as requiring representation in local government institutions?',
            'options' => [
                'A' => 'Bankers',
                'B' => 'Peasants',
                'C' => 'Industrialists only',
                'D' => 'Judges',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'Which of the following is included in the definition of "building" under the Act?',
            'options' => [
                'A' => 'Only residential houses',
                'B' => 'Only commercial plazas',
                'C' => 'Shops, houses, huts, sheds, walls and other specified structures',
                'D' => 'Only government buildings',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-I: KP Local Government Act, 2013',
            'text' => 'Under the Act, biometric means measurement and analysis of unique physical characteristics for:',
            'options' => [
                'A' => 'Property taxation',
                'B' => 'Verification and authentication of voter identity',
                'C' => 'Employee attendance',
                'D' => 'Vehicle registration',
            ],
            'answer' => 'B',
        ],

        // Part-II: Local Government Structure
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'The Act provides for different levels of local government including:',
            'options' => [
                'A' => 'Tehsil, Village and Neighbourhood levels',
                'B' => 'Federal, Provincial and District levels only',
                'C' => 'Division and Province only',
                'D' => 'Union and Federation only',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'The Tehsil Local Government is headed by the:',
            'options' => [
                'A' => 'Deputy Commissioner',
                'B' => 'Assistant Commissioner',
                'C' => 'Chairman',
                'D' => 'District Police Officer',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'Under the 2022 amendments, the executive authority of Tehsil Local Government vests in the:',
            'options' => [
                'A' => 'Tehsil Council',
                'B' => 'Chairman',
                'C' => 'Assistant Commissioner',
                'D' => 'Secretary Local Government',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'Under the 2022 amendment, the Chairman, Tehsil Local Government exercises powers and functions as:',
            'options' => [
                'A' => 'Prescribed by rules',
                'B' => 'Prescribed by the Constitution only',
                'C' => 'Directed by the District Police Officer',
                'D' => 'Determined by the Federal Government',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'The functions and powers of the Chairman, Tehsil Local Government are principally dealt with after the 2022 amendment through:',
            'options' => [
                'A' => 'Rules',
                'B' => 'Federal laws',
                'C' => 'Police regulations',
                'D' => 'Court judgments only',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'The Tehsil Council may approve:',
            'options' => [
                'A' => 'Federal taxes',
                'B' => 'Taxes, fines and penalties proposed by the Chairman, subject to law',
                'C' => 'Provincial budget',
                'D' => 'Federal budget',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'Which body reviews performance reports presented by the Chairman, Tehsil Local Government?',
            'options' => [
                'A' => 'Tehsil Council',
                'B' => 'Supreme Court',
                'C' => 'Provincial Assembly',
                'D' => 'District Police',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'A Tehsil Council may elect one of its members to:',
            'options' => [
                'A' => 'Become Deputy Commissioner',
                'B' => 'Preside over meetings of the Tehsil Council',
                'C' => 'Become District Judge',
                'D' => 'Become Secretary Finance',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'During temporary absence of the Chairman, the elected Presiding Officer may:',
            'options' => [
                'A' => 'Deputize the Chairman as provided by law',
                'B' => 'Dissolve the Council',
                'C' => 'Appoint the Deputy Commissioner',
                'D' => 'Approve the provincial budget',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-II: Local Government Structure',
            'text' => 'The 2022 amendment inserted provisions relating to the:',
            'options' => [
                'A' => 'City Local Council',
                'B' => 'Federal Council',
                'C' => 'Provincial Council',
                'D' => 'District Police Council',
            ],
            'answer' => 'A',
        ],

        // Part-III: 2022 Amendments
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'The Khyber Pakhtunkhwa Local Government (Amendment) Act, 2022 is:',
            'options' => [
                'A' => 'Act No. XII of 2022',
                'B' => 'Act No. XXII of 2022',
                'C' => 'Act No. XXVIII of 2022',
                'D' => 'Act No. XXXII of 2022',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'The 2022 Amendment Act was passed on:',
            'options' => [
                'A' => '3 June 2022',
                'B' => '8 June 2022',
                'C' => '20 June 2022',
                'D' => '23 June 2022',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'The 2022 Amendment Act came into force on:',
            'options' => [
                'A' => '3 June 2022',
                'B' => '8 June 2022',
                'C' => '20 June 2022',
                'D' => '1 July 2022',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'Section 23A after the 2022 amendment relates to the functions and powers of the:',
            'options' => [
                'A' => 'Mayor',
                'B' => 'Chairman, Tehsil Local Government',
                'C' => 'Deputy Commissioner',
                'D' => 'Chief Secretary',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'Under the 2022 amendment, Section 23B was:',
            'options' => [
                'A' => 'Expanded',
                'B' => 'Replaced',
                'C' => 'Deleted',
                'D' => 'Renumbered as Section 25B',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'Section 25A after the 2022 amendment deals with the functions of the:',
            'options' => [
                'A' => 'Mayor, City Local Government',
                'B' => 'Chairman, Village Council',
                'C' => 'Assistant Commissioner',
                'D' => 'District Commissioner',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'The 2022 amendment inserted Section 25B relating to the:',
            'options' => [
                'A' => 'City Local Council',
                'B' => 'Provincial Assembly',
                'C' => 'District Council',
                'D' => 'Local Government Commission',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'Under the 2022 amendment, the Provincial Government\'s power to develop, approve and implement schemes or projects for the City Local Government is:',
            'options' => [
                'A' => 'Removed',
                'B' => 'Preserved',
                'C' => 'Transferred to Federal Government',
                'D' => 'Transferred to Village Council',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'The 2022 amendment changed the definition of municipal services to include matters such as:',
            'options' => [
                'A' => 'Water supply and sanitation',
                'B' => 'Foreign affairs',
                'C' => 'Defence',
                'D' => 'Currency regulation',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-III: 2022 Amendments',
            'text' => 'Which of the following is included within municipal services under the amended framework?',
            'options' => [
                'A' => 'Fire fighting',
                'B' => 'Foreign trade',
                'C' => 'National defence',
                'D' => 'Currency printing',
            ],
            'answer' => 'A',
        ],

        // Part-IV: Tehsil/City Local Government Rules of Business, 2022
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => 'The City/Tehsil Local Government Rules of Business, 2022 were promulgated under which section of the Act?',
            'options' => [
                'A' => 'Section 25',
                'B' => 'Section 30',
                'C' => 'Section 112',
                'D' => 'Section 113',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => 'The Rules of Business, 2022 were notified on:',
            'options' => [
                'A' => '8 June 2022',
                'B' => '20 June 2022',
                'C' => '23 June 2022',
                'D' => '30 June 2022',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => 'The City/Tehsil Rules of Business, 2022 apply to:',
            'options' => [
                'A' => 'Tehsil Local Government only',
                'B' => 'City Local Government only',
                'C' => 'Tehsil Local Government, City Local Government and Capital Metropolitan Government Peshawar',
                'D' => 'Provincial Government only',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => 'Under the Rules, reference to "Tehsil Local Government" is to be read as including:',
            'options' => [
                'A' => 'City Local Government and Capital Metropolitan Government Peshawar',
                'B' => 'District Government only',
                'C' => 'Village Council only',
                'D' => 'Provincial Government',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => 'Under the Rules, "Chairman, Tehsil Local Government and Tehsil Council" is to be read as:',
            'options' => [
                'A' => 'Deputy Commissioner',
                'B' => 'Mayor, City Local Government and City Local Council, and Mayor, Capital Metropolitan Government Peshawar',
                'C' => 'Chief Minister',
                'D' => 'Assistant Commissioner',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => '"Tehsil Local Administration" is to be read as:',
            'options' => [
                'A' => 'District Administration',
                'B' => 'City Local Administration',
                'C' => 'Provincial Administration',
                'D' => 'Federal Administration',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => 'The Rules of Business, 2022 came into force:',
            'options' => [
                'A' => 'After six months',
                'B' => 'After approval by the Federal Government',
                'C' => 'At once',
                'D' => 'From 1 January 2023',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => 'Under the Rules, an Assistant Commissioner includes:',
            'options' => [
                'A' => 'Only the Deputy Commissioner',
                'B' => 'Additional Assistant Commissioners',
                'C' => 'District Police Officer',
                'D' => 'Tehsil Municipal Officer',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => 'The Assistant Commissioner is described in the Rules as the:',
            'options' => [
                'A' => 'Chief Financial Officer',
                'B' => 'Coordinating head of the Tehsil Local Government',
                'C' => 'Head of the Provincial Government',
                'D' => 'Head of the Local Government Commission',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-IV: Tehsil/City Local Government Rules of Business, 2022',
            'text' => '"Own source revenue" means revenue generated by the concerned Tehsil Local Government under:',
            'options' => [
                'A' => 'Section 25',
                'B' => 'Section 30(2)',
                'C' => 'Section 42',
                'D' => 'Section 54',
            ],
            'answer' => 'B',
        ],

        // Part-V: Local Government Funds, Property and Committees
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'The Tehsil Fund is established for crediting:',
            'options' => [
                'A' => 'Federal taxes only',
                'B' => 'Various monies in pursuance of Section 30',
                'C' => 'Police fines only',
                'D' => 'Court fees only',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'Under the Rules of Business, the Tehsil Municipal Officer is responsible for:',
            'options' => [
                'A' => 'Municipal services',
                'B' => 'Provincial taxation',
                'C' => 'Federal administration',
                'D' => 'Judicial administration',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'Municipal services are specified in which column of the Tenth Schedule?',
            'options' => [
                'A' => 'First column',
                'B' => 'Second column',
                'C' => 'Third column',
                'D' => 'Fourth column',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'Social services are specified in which column of the Tenth Schedule?',
            'options' => [
                'A' => 'First column',
                'B' => 'Second column',
                'C' => 'Third column',
                'D' => 'Fourth column',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'A Standing Committee is elected under:',
            'options' => [
                'A' => 'Section 25 of the Act',
                'B' => 'Section 42 only',
                'C' => 'Section 54 only',
                'D' => 'Section 112 only',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'The Rules define a Presiding Officer as:',
            'options' => [
                'A' => 'The Deputy Commissioner',
                'B' => 'A member elected by the Tehsil Council to preside over its meetings',
                'C' => 'The Tehsil Municipal Officer',
                'D' => 'The Assistant Commissioner',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'Bye-laws made by the Tehsil Council are referred to in the Rules as being made under Section 113 read with:',
            'options' => [
                'A' => 'Section 5',
                'B' => 'Section 25',
                'C' => 'Section 30',
                'D' => 'Section 54',
            ],
            'answer' => 'B',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'The Local Government Commission is established under:',
            'options' => [
                'A' => 'Section 25',
                'B' => 'Section 30',
                'C' => 'Section 54',
                'D' => 'Section 112',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'The Local Government Commission\'s rules of business provide for functions including:',
            'options' => [
                'A' => 'Inspection and inquiry relating to local governments',
                'B' => 'National defence',
                'C' => 'Federal taxation',
                'D' => 'Foreign policy',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Part-V: Local Government Funds, Property and Committees',
            'text' => 'Under the Local Government Commission Conduct of Business Rules, 2015, the headquarters of the Commission is at:',
            'options' => [
                'A' => 'Islamabad',
                'B' => 'Peshawar',
                'C' => 'Abbottabad',
                'D' => 'Mardan',
            ],
            'answer' => 'B',
        ],

        // Bonus MCQs: More Difficult / Test-Level Questions
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'The Khyber Pakhtunkhwa Local Government Act, 2013 was first published after receiving the assent of the Governor in the Gazette dated:',
            'options' => [
                'A' => '7 November 2013',
                'B' => '20 June 2013',
                'C' => '14 August 2013',
                'D' => '23 March 2013',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'The Seventh Schedule of the Act primarily contains:',
            'options' => [
                'A' => 'Rules and bye-laws',
                'B' => 'Provincial taxes',
                'C' => 'Electoral constituencies only',
                'D' => 'Judicial procedures',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'Which of the following is included among the rules listed in the Seventh Schedule?',
            'options' => [
                'A' => 'Local Government Procurement',
                'B' => 'Federal Service Rules',
                'C' => 'Pakistan Army Rules',
                'D' => 'Foreign Service Rules',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'Which of the following is a subject of bye-laws under the Seventh Schedule?',
            'options' => [
                'A' => 'Prevention of encroachments',
                'B' => 'Foreign affairs',
                'C' => 'National defence',
                'D' => 'Customs duties',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'The Act provides for rules relating to Local Government:',
            'options' => [
                'A' => 'Internal Audit',
                'B' => 'Foreign Affairs',
                'C' => 'Defence Procurement',
                'D' => 'Customs',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'Which of the following is included among the listed Local Government rules?',
            'options' => [
                'A' => 'Fiscal Transfers',
                'B' => 'Foreign Exchange',
                'C' => 'Banking Regulation',
                'D' => 'National Security',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'Under the 2022 City/Tehsil Rules, "property" refers to property specified under:',
            'options' => [
                'A' => 'Section 25',
                'B' => 'Section 30',
                'C' => 'Section 38(1)',
                'D' => 'Section 54',
            ],
            'answer' => 'C',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'The Tehsil Council is responsible for reviewing reports and recommendations of the:',
            'options' => [
                'A' => 'Tehsil Accounts Committee',
                'B' => 'Provincial Finance Commission',
                'C' => 'Federal Public Accounts Committee',
                'D' => 'District Police Committee',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'The City Local Council may approve plans relating to:',
            'options' => [
                'A' => 'Sewerage networks and treatment plants',
                'B' => 'National highways only',
                'C' => 'Foreign embassies',
                'D' => 'Defence installations',
            ],
            'answer' => 'A',
        ],
        [
            'part' => 'Bonus MCQs: More Difficult / Test-Level Questions',
            'text' => 'Which institution can develop, approve and implement a scheme or project for the City Local Government despite the functions assigned to the City Local Council?',
            'options' => [
                'A' => 'Provincial Government',
                'B' => 'Village Council',
                'C' => 'Neighbourhood Council',
                'D' => 'Tehsil Accounts Committee',
            ],
            'answer' => 'A',
        ],
    ],
];
