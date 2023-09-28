import PageComponent from '../components/PageComponent';
import SurveyListItem from '../components/SurveyListItem';

import { userStateContext } from '../contexts/ContextProvider';

export default function Surveys(){
	const { surveys } = userStateContext();
	console.log(surveys);
	const onDeleteClick= () => {
		console.log('On Delete'); 
	}
	return (
		<>
		<PageComponent title="Surveys">
			<div className="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-4" >
				{surveys.map((survey) =>(
					<SurveyListItem surveys={survey} key={survey.id}  onDeleteClick={onDeleteClick} />
				 ))}
			</div>
		</PageComponent>
		</>
	)
}