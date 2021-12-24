import React, { Component } from 'react';
import { HashRouter as Router, Route, browserHistory } from 'react-router-dom';
import { Login, Home, Products, ManageTables, PrintTables, PrintBill, PrintOrder, NewPlace,
				 ManageOrders, ManageBills, ManageUsers, Tables, Place, ManagePlaces, Dashboard, AdminConfig } from './Screen';
import moment from 'moment';

const AppRoute = ({ component: Component, ...rest }) => (
	<Route {...rest} render={props => (
		<Component {...props} {...rest} />
	)} />
)

export default class App extends Component
{
	constructor() {
		super()

		this.state = {
			appLoading: true,
			user: { uid: false }
		};
		moment.locale('pt-br');
	}

	render() {
		return (
			<Router history={browserHistory}>
				<div id="app">
					<Route exact path="/" component={ Home } />
					<Route path="/login" component={ Login } />
					<Route path="/logout" component={ Login } />
					<Route path="/place" component={ Place } />
					<Route path="/manage_places" component={ ManagePlaces } />
					<Route path="/products" component={ Products } />
					<Route path="/users" component={ ManageUsers } />
					<Route path="/users_admin" component={ ManageUsers } />
					<Route path="/orders" component={ ManageOrders } />
					<Route path="/bills" component={ ManageBills } />
					<Route path="/manage_tables" component={ ManageTables } />
					<Route path="/tables" component={ Tables } />
					<Route path="/dashboard" component={ Dashboard } />
					<Route path="/dashboards" component={ Dashboard } />
					<Route path="/admin_config" component={ AdminConfig } />
					<Route path="/table_print/:tableKey/:logo" component={ PrintTables } />
					<Route path="/bill_print/:billKey" component={ PrintBill } />
					<Route path="/order_print/:checkinKey/:orderKey" component={ PrintOrder } />
					<Route path="/newplace/:plan" component={ NewPlace } />
				</div>
			</Router>
		);
	}
}
