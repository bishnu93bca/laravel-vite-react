import { useContext, useState, createContext } from 'react';


const StateContext = createContext({
	currentUser: {},
	userToken: null,
	surveys:[],
	setCurrentUser: () => {},
	setUserToken: () => {},
});

const tempSurveys=[
{
	"id":1,
	"image_url":"link",
	"title":"Title Demo",
	"slug": "slug-dekmo",
	"status":true,
	"description":"Description",
},
{
	"id":2,
	"image_url":"link",
	"title":"Title Demo",
	"slug": "slug-dekmo",
	"status":true,
	"description":"Description",
},
{
	"id":3,
	"image_url":"link",
	"title":"Title Demo",
	"slug": "slug-dekmo",
	"status":true,
	"description":"Description",
}
,{
	"id":4,
	"image_url":"link",
	"title":"Title Demo",
	"slug": "slug-dekmo",
	"status":true,
	"description":"Description",
},
{
	"id":5,
	"image_url":"link",
	"title":"Title Demo",
	"slug": "slug-dekmo",
	"status":true,
	"description":"Description",
}

];

export const ContextProvider =({children}) =>{
	const [currentUser, setCurrentUser] = useState({});
	const [userToken, _setUserToken] = useState(localStorage.getItem('ACCESS_TOKEN'));;
	const [surveys,setSurveys] = useState(tempSurveys);

	const setUserToken = (token) => {
		
    if (token) {
      localStorage.setItem('ACCESS_TOKEN', token);
    } else {
      localStorage.removeItem('ACCESS_TOKEN');
    }
	_setUserToken(token)
  }

  const setNotification = message => {
    _setNotification(message);

    setTimeout(() => {
      _setNotification('')
    }, 5000)
  }
	return (
		<StateContext.Provider 
		value={{
			currentUser,
			setCurrentUser,
			userToken,
			setUserToken,
			surveys,
		}}>

		{children}
		</StateContext.Provider>
	)
}

export const userStateContext = () => useContext(StateContext);

